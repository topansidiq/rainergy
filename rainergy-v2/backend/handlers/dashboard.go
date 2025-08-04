package handlers

import (
	"rainergy-v2/models"
	"rainergy-v2/server"

	"github.com/gofiber/fiber/v2"
)

func ShowDashboard(c *fiber.Ctx) error {
	var monitors []models.Panels

	result := server.Database.
		Order("timestamp desc").
		Limit(10).
		Find(&monitors)

	if result.Error != nil {
		return c.Status(500).SendString("Gagal mengambil data monitoring")
	}

	var latest models.Panels
	if len(monitors) > 0 {
		latest = monitors[0]
	}

	return c.Render("dashboard", fiber.Map{
		"Title":  "Dashboard Monitoring",
		"Data":   monitors,
		"Latest": latest,
	})
}
