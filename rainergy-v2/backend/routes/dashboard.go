package routes

import (
	"rainergy-v2/handlers"

	"github.com/gofiber/fiber/v2"
)

func RegisterDashboardRoutes(app *fiber.App) {
	app.Get("/dashboard", handlers.ShowDashboard)
}
