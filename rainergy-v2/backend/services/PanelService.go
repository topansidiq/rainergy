package services

import (
	"math"
	"rainergy-v2/models"
	"rainergy-v2/server"
	"time"

	"github.com/gofiber/fiber/v2"
)

func round(val float64, precision int) float64 {
	factor := math.Pow(10, float64(precision))
	return math.Round(val*factor) / factor
}

func SavePanelReading(data models.Panels) models.Panels {
	data.Power = round(data.Voltage*data.Current, 2)

	data.Energy = round(data.Power*(1.0/60.0), 3)

	return data
}

func UpdatePanel(c *fiber.Ctx) error {
	var input models.Panels

	if err := c.BodyParser(&input); err != nil {
		return c.Status(fiber.StatusBadRequest).JSON(fiber.Map{"Error": "Invalid input", "Description": err.Error()})
	}

	var panel models.Panels
	if err := server.Database.First(&panel, input.ID).Error; err != nil {
		return c.Status(fiber.StatusNotFound).JSON(fiber.Map{"Error": "Panel not found!", "Description": err.Error()})
	}

	panel.Environment = input.Environment
	panel.Status = input.Status
	panel.UpdatedAt = input.UpdatedAt

	if err := server.Database.Save(&panel).Error; err != nil {
		return c.Status(fiber.StatusInternalServerError).JSON(fiber.Map{"Error": "Failed to updated panel", "Description": err.Error()})
	}

	return c.JSON(fiber.Map{
		"message": "Panel update successfully",
		"panel":   panel,
		"date":    time.Now(),
	})
}
