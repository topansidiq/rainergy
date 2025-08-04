package server

import (
	"rainergy-v2/models"

	"gorm.io/driver/mysql"
	"gorm.io/gorm"
)

var Database *gorm.DB

func Connect() {
	dsn := "root:@tcp(127.0.0.1:3306)/rainergy?charset=utf8mb4&parseTime=True&loc=Local"
	database, err := gorm.Open(mysql.Open(dsn), &gorm.Config{})

	if err != nil {
		panic("Failed to connect to database: " + err.Error())
	}

	Database = database

	Database.AutoMigrate(&models.Units{}, &models.Panels{})
}
