<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Cinema;
use App\Models\Seat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with('cinema')->orderBy('created_at', 'desc')->get();
        return view('admin.rooms.index', compact('rooms'));
    }
    
    public function create()
    {
        $cinemas = Cinema::where('is_active', true)->get();
        return view('admin.rooms.create', compact('cinemas'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'cinema_id' => 'required|exists:cinemas,id',
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1|max:500',
            'type' => 'required|in:STANDARD,VIP,IMAX,4DX',
            'is_active' => 'boolean',
        ]);
        
        Room::create($request->all());
        
        return redirect()->route('admin.rooms.index')
            ->with('success', 'Phòng chiếu đã được thêm thành công!');
    }
    
    public function show(Room $room)
    {
        $room->load(['cinema', 'seats', 'showtimes.movie']);
        return view('admin.rooms.show', compact('room'));
    }
    
    public function edit(Room $room)
    {
        $cinemas = Cinema::where('is_active', true)->get();
        return view('admin.rooms.edit', compact('room', 'cinemas'));
    }
    
    public function update(Request $request, Room $room)
    {
        $request->validate([
            'cinema_id' => 'required|exists:cinemas,id',
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1|max:500',
            'type' => 'required|in:STANDARD,VIP,IMAX,4DX',
            'is_active' => 'boolean',
            'num_rows' => 'nullable|integer|min:1|max:30',
            'seats_per_row' => 'nullable|integer|min:1|max:30',
            'standard_count' => 'nullable|integer|min:0',
            'vip_count' => 'nullable|integer|min:0',
            'couple_count' => 'nullable|integer|min:0',
            'vip_start_row' => 'nullable|string|max:1',
            'vip_end_row' => 'nullable|string|max:1',
            'couple_position' => 'nullable|in:end,last_row,both',
            'couple_per_row' => 'nullable|integer|min:1|max:5',
            'standard_price' => 'nullable|numeric|min:0',
            'vip_price' => 'nullable|numeric|min:0',
            'couple_price' => 'nullable|numeric|min:0',
            'generate_seats' => 'nullable|boolean',
        ]);
        
        $room->update($request->only(['cinema_id', 'name', 'capacity', 'type', 'is_active']));
        
        // Generate seats if requested
        if ($request->has('generate_seats') && $request->generate_seats) {
            // Check if room has any tickets
            $hasTickets = $room->seats()->whereHas('tickets')->exists();
            
            if ($hasTickets) {
                return redirect()->back()
                    ->with('error', 'Không thể tạo lại ghế vì phòng đã có vé được bán!');
            }
            
            try {
                DB::beginTransaction();
                
                // Delete existing seats
                $room->seats()->delete();
                
                // Generate new seats
                $this->generateSeats($room, $request);
                
                DB::commit();
                
                return redirect()->route('admin.rooms.index')
                    ->with('success', 'Phòng chiếu và ghế đã được cập nhật thành công!');
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'Có lỗi xảy ra khi tạo ghế: ' . $e->getMessage());
            }
        }
        
        return redirect()->route('admin.rooms.index')
            ->with('success', 'Phòng chiếu đã được cập nhật thành công!');
    }
    
    /**
     * Generate seats for a room based on configuration
     */
    private function generateSeats(Room $room, Request $request)
    {
        $numRows = $request->input('num_rows', ceil($room->capacity / 18));
        $seatsPerRow = $request->input('seats_per_row', 18);
        $totalSeats = $numRows * $seatsPerRow;
        
        // Pricing
        $basePrice = match($room->type) {
            'IMAX' => $request->input('standard_price', 150000),
            '4DX' => $request->input('standard_price', 200000),
            'VIP' => $request->input('standard_price', 180000),
            default => $request->input('standard_price', 80000),
        };
        $vipPrice = $request->input('vip_price', $basePrice + 50000);
        $couplePrice = $request->input('couple_price', $basePrice * 2);
        
        // Seat counts
        $standardCount = $request->input('standard_count', 0);
        $vipCount = $request->input('vip_count', 0);
        $coupleCount = $request->input('couple_count', 0);
        
        // Validate total if specific counts are provided
        if ($standardCount > 0 || $vipCount > 0 || $coupleCount > 0) {
            $inputTotal = $standardCount + $vipCount + $coupleCount;
            if ($inputTotal != $totalSeats) {
                throw new \Exception("Tổng số ghế nhập ($inputTotal) không khớp với tổng ghế ($totalSeats). Vui lòng điều chỉnh lại.");
            }
        }
        
        // VIP row configuration
        $vipStartRow = $request->input('vip_start_row');
        $vipEndRow = $request->input('vip_end_row');
        $rows = array_slice(range('A', 'Z'), 0, $numRows);
        
        // Auto calculate VIP rows if not specified
        if (!$vipStartRow || !$vipEndRow) {
            $midPoint = floor($numRows / 2);
            $vipStartIndex = max(2, $midPoint - 2);
            $vipEndIndex = min($numRows - 1, $midPoint + 2);
            $vipStartRow = $rows[$vipStartIndex];
            $vipEndRow = $rows[$vipEndIndex];
        }
        
        $vipStartIndex = array_search($vipStartRow, $rows);
        $vipEndIndex = array_search($vipEndRow, $rows);
        
        // Couple seat configuration
        $couplePosition = $request->input('couple_position', 'end');
        $couplePerRow = $request->input('couple_per_row', 2);
        
        // Calculate seat distribution if counts are 0
        if ($standardCount == 0 && $vipCount == 0 && $coupleCount == 0) {
            // Auto distribution
            $vipSeats = 0;
            $coupleSeats = 0;
            
            // Count VIP seats in VIP rows (excluding couple positions)
            for ($i = $vipStartIndex; $i <= $vipEndIndex && $i < count($rows); $i++) {
                $vipSeats += $seatsPerRow;
            }
            
            // Count couple seats
            if ($couplePosition == 'end') {
                $coupleSeats = $numRows * $couplePerRow;
            } elseif ($couplePosition == 'last_row') {
                $coupleSeats = $seatsPerRow;
            } elseif ($couplePosition == 'both') {
                // End of each row + last row (avoid double counting last row)
                $coupleSeats = ($numRows - 1) * $couplePerRow + $seatsPerRow;
            }
            
            // Adjust to not exceed total and reasonable limits
            $coupleSeats = min($coupleSeats, floor($totalSeats * 0.15)); // Max 15% couple seats
            $vipSeats = min($vipSeats, floor($totalSeats * 0.4)); // Max 40% VIP seats
            
            // Ensure we don't exceed total
            $remaining = $totalSeats - $coupleSeats;
            $vipSeats = min($vipSeats, $remaining);
            $standardCount = $totalSeats - $vipSeats - $coupleSeats;
            $vipCount = $vipSeats;
            $coupleCount = $coupleSeats;
        }
        
        // Build seat list with priorities
        $seatList = [];
        foreach ($rows as $rowIndex => $row) {
            $isVipRow = $rowIndex >= $vipStartIndex && $rowIndex <= $vipEndIndex;
            $isLastRow = $rowIndex == count($rows) - 1;
            
            for ($col = 1; $col <= $seatsPerRow; $col++) {
                $isCouplePosition = false;
                if ($couplePosition == 'end' && $col > ($seatsPerRow - $couplePerRow)) {
                    $isCouplePosition = true;
                } elseif ($couplePosition == 'last_row' && $isLastRow) {
                    $isCouplePosition = true;
                } elseif ($couplePosition == 'both' && ($isLastRow || $col > ($seatsPerRow - $couplePerRow))) {
                    $isCouplePosition = true;
                }
                
                $seatList[] = [
                    'row' => $row,
                    'rowIndex' => $rowIndex,
                    'col' => $col,
                    'isVipRow' => $isVipRow,
                    'isCouplePos' => $isCouplePosition,
                    'priority' => $isCouplePosition ? 1 : ($isVipRow ? 2 : 3), // Lower = higher priority
                ];
            }
        }
        
        // Sort by priority
        usort($seatList, function($a, $b) {
            if ($a['priority'] != $b['priority']) {
                return $a['priority'] - $b['priority'];
            }
            if ($a['rowIndex'] != $b['rowIndex']) {
                return $a['rowIndex'] - $b['rowIndex'];
            }
            return $a['col'] - $b['col'];
        });
        
        // Assign seat types based on counts
        $currentStandard = 0;
        $currentVip = 0;
        $currentCouple = 0;
        
        foreach ($seatList as &$seat) {
            if ($seat['isCouplePos'] && $currentCouple < $coupleCount) {
                $seat['type'] = 'COUPLE';
                $seat['price'] = $couplePrice;
                $currentCouple++;
            } elseif ($seat['isVipRow'] && !$seat['isCouplePos'] && $currentVip < $vipCount) {
                $seat['type'] = 'VIP';
                $seat['price'] = $vipPrice;
                $currentVip++;
            } else {
                $seat['type'] = 'STANDARD';
                $seat['price'] = $basePrice;
                $currentStandard++;
            }
            
            // Price adjustment for middle rows (better view)
            if ($seat['rowIndex'] >= floor($numRows * 0.3) && $seat['rowIndex'] <= floor($numRows * 0.7)) {
                $seat['price'] += 10000;
            }
        }
        
        // Sort back to display order
        usort($seatList, function($a, $b) {
            if ($a['rowIndex'] != $b['rowIndex']) {
                return $a['rowIndex'] - $b['rowIndex'];
            }
            return $a['col'] - $b['col'];
        });
        
        // Create seats
        foreach ($seatList as $seat) {
            Seat::create([
                'room_id' => $room->id,
                'row' => $seat['row'],
                'number' => $seat['col'],
                'type' => $seat['type'],
                'price' => $seat['price'],
                'is_active' => true,
            ]);
        }
        
        // Update room capacity to match actual seats
        $actualCapacity = Seat::where('room_id', $room->id)->count();
        $room->update(['capacity' => $actualCapacity]);
    }
    
    public function destroy(Room $room)
    {
        // Kiểm tra nếu có showtime đang sử dụng phòng này
        if ($room->showtimes()->where('date', '>=', now()->toDateString())->exists()) {
            return redirect()->route('admin.rooms.index')
                ->with('error', 'Không thể xóa phòng chiếu đang có lịch chiếu trong tương lai!');
        }
        
        $room->delete();
        
        return redirect()->route('admin.rooms.index')
            ->with('success', 'Phòng chiếu đã được xóa thành công!');
    }
}
