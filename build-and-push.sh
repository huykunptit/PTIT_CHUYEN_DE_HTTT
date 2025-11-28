#!/bin/bash

# Script to build and push Docker image to Docker Hub for Railway deployment

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Configuration
DOCKER_USERNAME="${DOCKER_USERNAME:-}"
IMAGE_NAME="${IMAGE_NAME:-cinema-app}"
VERSION="${VERSION:-latest}"
DOCKERFILE="${DOCKERFILE:-Dockerfile.railway}"

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    echo -e "${RED}Error: Docker is not running. Please start Docker first.${NC}"
    exit 1
fi

# Check if Dockerfile exists
if [ ! -f "$DOCKERFILE" ]; then
    echo -e "${RED}Error: Dockerfile '$DOCKERFILE' not found!${NC}"
    exit 1
fi

# Get Docker username if not set
if [ -z "$DOCKER_USERNAME" ]; then
    echo -e "${YELLOW}Docker Hub username not set.${NC}"
    read -p "Enter your Docker Hub username: " DOCKER_USERNAME
fi

# Full image name
FULL_IMAGE_NAME="$DOCKER_USERNAME/$IMAGE_NAME:$VERSION"
LATEST_IMAGE_NAME="$DOCKER_USERNAME/$IMAGE_NAME:latest"

echo -e "${GREEN}Building Docker image...${NC}"
echo "Image: $FULL_IMAGE_NAME"
echo "Dockerfile: $DOCKERFILE"
echo ""

# Build the image
docker build -f "$DOCKERFILE" -t "$FULL_IMAGE_NAME" -t "$LATEST_IMAGE_NAME" .

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ Build successful!${NC}"
else
    echo -e "${RED}❌ Build failed!${NC}"
    exit 1
fi

# Ask if user wants to push
read -p "Do you want to push the image to Docker Hub? (y/n) " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo -e "${YELLOW}Image built but not pushed.${NC}"
    echo "To push manually, run:"
    echo "  docker push $FULL_IMAGE_NAME"
    echo "  docker push $LATEST_IMAGE_NAME"
    exit 0
fi

# Check if logged in to Docker Hub
if ! docker info | grep -q "Username"; then
    echo -e "${YELLOW}Not logged in to Docker Hub.${NC}"
    docker login
fi

# Push the image
echo -e "${GREEN}Pushing image to Docker Hub...${NC}"
docker push "$FULL_IMAGE_NAME"
docker push "$LATEST_IMAGE_NAME"

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ Push successful!${NC}"
    echo ""
    echo -e "${GREEN}Image is now available at:${NC}"
    echo "  $FULL_IMAGE_NAME"
    echo "  $LATEST_IMAGE_NAME"
    echo ""
    echo -e "${GREEN}Next steps:${NC}"
    echo "1. Go to Railway Dashboard: https://railway.com/"
    echo "2. Create a new project"
    echo "3. Select 'Deploy from Docker Hub'"
    echo "4. Enter image name: $LATEST_IMAGE_NAME"
    echo "5. Configure environment variables"
    echo "6. Deploy!"
else
    echo -e "${RED}❌ Push failed!${NC}"
    exit 1
fi

