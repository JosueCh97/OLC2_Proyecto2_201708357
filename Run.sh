#!/bin/bash
set -e

ROOT_DIR="$(cd "$(dirname "$0")" && pwd)"

cd "$ROOT_DIR/BackEnd"
php -S 127.0.0.1:8000 > /tmp/olc2p1-backend.log 2>&1 &
BACKEND_PID=$!

cleanup() {
	kill "$BACKEND_PID" 2>/dev/null || true
}

trap cleanup EXIT INT TERM

cd "$ROOT_DIR/Frontend"
npm run dev