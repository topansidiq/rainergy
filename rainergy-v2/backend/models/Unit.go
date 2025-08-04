package models

import (
	"time"
)

type Units struct {
	ID           uint      `gorm:"primaryKey" json:"id"`
	Panels       []Panels  `gorm:"foreignKey:UnitID"`
	Installed_at time.Time `json:"installed_at"`
	Voltages     float64   `json:"voltages"`
	Powers       float64   `json:"powers"`
}
