run:
    composer run dev

biome:
    npx biome check --write

up:
    tmux new-session -d -s "preums"
    tmux send-keys -t "preums" "just run" ENTER

open:
    open "http://localhost:8000/"
