package main

import (
	"fmt"
	"rainergy-v2/routes"
	"rainergy-v2/server"

	"github.com/gofiber/fiber/v2"
	"github.com/gofiber/template/html/v2"
)

func main() {
	server.Connect()

	engine := html.New("../frontend/resource/views", ".html")

	app := fiber.New(fiber.Config{
		Views: engine,
	})

	app.Static("/", "./frontend/public")

	routes.RegisterDashboardRoutes(app)
	routes.RegisterMonitorRoutes(app)

	err := app.Listen(":3000")

	if err != nil {
		panic("Failed to start server: " + err.Error())
	}

	fmt.Println("Server running at http://127.0.0.1:3000")
}
