package models

import "time"

type Panels struct {
	ID          uint      `gorm:"primaryKey" json:"id"`
	UnitID      uint      `gorm:"not null"`
	DustLevel   float64   `json:"dust_level"`
	Voltage     float64   `json:"voltage"`
	Current     float64   `json:"current"`
	Power       float64   `json:"power"`
	Energy      float64   `json:"energy"`
	Status      string    `json:"status"`
	Irradiance  float64   `json:"irradiance"`
	Temperature float64   `json:"temperature"`
	Weather     string    `json:"weather"`
	Environment string    `json:"environment"`
	InstalledAt time.Time `json:"installed_at"`
	UpdatedAt   time.Time `json:"updated_at"`
}
