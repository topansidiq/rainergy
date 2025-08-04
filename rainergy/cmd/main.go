package main

import (
	"log"
	"net/http"
	"os"
	"os/exec"

	"github.com/topansidiq/rainergy/internal/config"
	"github.com/topansidiq/rainergy/internal/firebase"
	"github.com/topansidiq/rainergy/internal/handlers"
)

func main() {
	config.InitConfig()
	firebase.InitFirebase()

	// Jalankan npm run dev (Tailwind watch)
	cmd := exec.Command("npm", "run", "dev")
	cmd.Stdout = os.Stdout
	cmd.Stderr = os.Stderr
	err := cmd.Start()
	if err != nil {
		log.Fatalf("Gagal menjalankan npm run dev: %v", err)
	}
	log.Println("Tailwind sedang watch dengan npm run dev...")

	// Serve file statis JS dan CSS
	http.Handle("/js/", http.StripPrefix("/js/", http.FileServer(http.Dir("public/js"))))
	http.Handle("/css/", http.StripPrefix("/css/", http.FileServer(http.Dir("public/css"))))

	// Route handler
	http.HandleFunc("/", handlers.DashboardHandler)
	http.HandleFunc("/api/power", handlers.DashboardHandler)

	log.Println("Server running at http://localhost:8080")
	log.Fatal(http.ListenAndServe(":8080", nil))
}
