package handlers

import (
	"rainergy-v2/models"
	"rainergy-v2/server"
	"rainergy-v2/services"
	"time"

	"github.com/gofiber/fiber/v2"
)

func CreateMonitorData(c *fiber.Ctx) error {
	data := new(models.Panels)

	if err := c.BodyParser(data); err != nil {
		return c.Status(400).JSON(fiber.Map{"error": "Invalid body"})
	}

	data.InstalledAt = time.Now()
	*data = services.SavePanelReading(*data)

	if err := server.Database.Create(&data).Error; err != nil {
		return c.Status(500).JSON(fiber.Map{"error": "Failed save data"})
	}

	return c.JSON(data)
}

func GetLatestMonitorData(c *fiber.Ctx) error {
	var data models.Panels

	if err := server.Database.First(&data).Error; err != nil || data.ID == 0 {
		if err != nil {
			return c.Status(400).JSON(fiber.Map{"error": "Data not found"})
		} else {
			return c.Status(400).JSON(fiber.Map{"data": "Empty"})
		}
	}

	if err := server.Database.Order("timestamp desc").First(&data).Error; err != nil {
		return c.Status(400).JSON(fiber.Map{"error": "Data not found"})
	}

	return c.JSON(data)
}

func GetMonitorHistory(c *fiber.Ctx) error {
	limit := c.QueryInt("limit", 20)

	var data []models.Panels

	if err := server.Database.Order("timestamp desc").Limit(limit).Find(&data).Error; err != nil || len(data) {
		return c.Status(500).JSON(fiber.Map{"error": "Failed to fetch data!"})
	}

	return c.JSON(data)
}
