package models

type Monitor struct {
	Voltage     float64 `json:"voltage"`
	Current     float64 `json:"current"`
	Power       float64 `json:"power"`
	Energy      float64 `json:"energy"`
	Intensity   float64 `json:"intensity"`
	Temperature float64 `json:"temperature"`
	Timestamp   int64   `json:"timestamp"`
}
