package routes

import (
	"rainergy-v2/handlers"

	"github.com/gofiber/fiber/v2"
)

func RegisterMonitorRoutes(app *fiber.App) {
	api := app.Group("api/monitor")

	api.Get("/", handlers.GetLatestMonitorData)
	api.Post("/", handlers.CreateMonitorData)
	api.Get("/history", handlers.GetMonitorHistory)
}
