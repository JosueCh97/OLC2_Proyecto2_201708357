#!/bin/bash
# ─────────────────────────────────────────────────────────
#  Run.sh — levanta backend (PHP) y frontend (Vite) juntos
#
#  Uso:   ./Run.sh
#  Logs:  /tmp/olc2p2-backend.log
# ─────────────────────────────────────────────────────────
set -e

ROOT_DIR="$(cd "$(dirname "$0")" && pwd)"
LOG_FILE="/tmp/olc2p2-backend.log"

echo ""
echo "  Golampi Compiler — entorno de desarrollo"
echo "  ─────────────────────────────────────────"
echo "  Backend  → http://127.0.0.1:8000"
echo "  Frontend → http://127.0.0.1:5173  (o el puerto que asigne Vite)"
echo "  Logs PHP → $LOG_FILE"
echo ""

# ── Backend PHP ──────────────────────────────────────────
cd "$ROOT_DIR/BackEnd"
php -S 127.0.0.1:8000 router.php > "$LOG_FILE" 2>&1 &
BACKEND_PID=$!
echo "  [backend]  PID $BACKEND_PID — iniciado"

cleanup() {
    echo ""
    echo "  Deteniendo servidores..."
    kill "$BACKEND_PID" 2>/dev/null || true
    echo "  Listo."
}
trap cleanup EXIT INT TERM

# ── Frontend Vite ─────────────────────────────────────────
cd "$ROOT_DIR/Frontend"
echo "  [frontend] Iniciando Vite..."
echo ""
npm run dev