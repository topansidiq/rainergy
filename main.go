// package main

// import (
// 	"database/sql"
// 	"log"
// 	"net/http"

// 	"github.com/gin-gonic/gin"
// 	_ "github.com/lib/pq"
// )

// var db *sql.DB

// func main() {
// 	var err error

// 	// Koneksi ke Neon DB
// 	db, err = sql.Open("postgres", "postgresql://neondb_owner:npg_vaf3LHCW4APn@ep-blue-lake-a7uz6qh4-pooler.ap-southeast-2.aws.neon.tech/neondb?sslmode=require")
// 	if err != nil {
// 		log.Fatalf("Failed to connect to database: %v", err)
// 	}
// 	defer db.Close()

// 	// Cek koneksi
// 	if err := db.Ping(); err != nil {
// 		log.Fatalf("Failed to ping database: %v", err)
// 	}

// 	// Setup router dengan Gin
// 	r := gin.Default()

// 	// Endpoint untuk menerima data dari transmitter
// 	r.POST("/api/measurements", func(c *gin.Context) {
// 		var data struct {
// 			Unit      string `json:"unit"`
// 			DustLevel int    `json:"dust_level"`
// 		}

// 		if err := c.ShouldBindJSON(&data); err != nil {
// 			c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
// 			return
// 		}

// 		// Insert data ke tabel measurements
// 		_, err := db.Exec("INSERT INTO measurements (unit, dust_level) VALUES ($1, $2)", data.Unit, data.DustLevel)
// 		if err != nil {
// 			log.Printf("Failed to insert data: %v", err)
// 			c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to insert data"})
// 			return
// 		}

// 		c.JSON(http.StatusOK, gin.H{"message": "Data inserted successfully"})
// 	})

// 	// Endpoint untuk menampilkan data
// 	r.GET("/api/measurements", func(c *gin.Context) {
// 		rows, err := db.Query("SELECT id, unit, dust_level, timestamp FROM measurements")
// 		if err != nil {
// 			log.Printf("Failed to fetch data: %v", err)
// 			c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to fetch data"})
// 			return
// 		}
// 		defer rows.Close()

// 		var measurements []gin.H
// 		for rows.Next() {
// 			var id int
// 			var unit string
// 			var dustLevel int
// 			var timestamp sql.NullTime

// 			// Scan hasil query
// 			if err := rows.Scan(&id, &unit, &dustLevel, &timestamp); err != nil {
// 				log.Printf("Failed to scan row: %v", err)
// 				c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to read data"})
// 				return
// 			}

// 			measurements = append(measurements, gin.H{
// 				"id":         id,
// 				"unit":       unit,
// 				"dust_level": dustLevel,
// 				"timestamp":  timestamp.Time, // sql.NullTime untuk menangani NULL
// 			})
// 		}

// 		// Jika ada error saat iterasi rows
// 		if err := rows.Err(); err != nil {
// 			log.Printf("Error during row iteration: %v", err)
// 			c.JSON(http.StatusInternalServerError, gin.H{"error": "Error fetching data"})
// 			return
// 		}

// 		c.JSON(http.StatusOK, measurements)
// 	})

// 	// Jalankan server pada port 8080
// 	if err := r.Run(":8080"); err != nil {
// 		log.Fatalf("Failed to run server: %v", err)
// 	}
// }

package main

import (
	"context"
	"database/sql"
	"html/template"
	"log"
	"net/http"

	"github.com/gin-gonic/gin"
	_ "github.com/lib/pq"
)

var db *sql.DB

func main() {
	var err error

	// Koneksi ke Neon DB
	db, err = sql.Open("postgres", "postgresql://neondb_owner:npg_vaf3LHCW4APn@ep-blue-lake-a7uz6qh4-pooler.ap-southeast-2.aws.neon.tech/neondb?sslmode=require")
	if err != nil {
		log.Fatalf("Failed to connect to database: %v", err)
	}

	// Cek koneksi
	if err := db.Ping(); err != nil {
		log.Fatalf("Failed to ping database: %v", err)
	}

	// Setup router dengan Gin
	r := gin.Default()

	// Endpoint untuk menampilkan halaman utama
	r.GET("/", func(c *gin.Context) {
		tmpl, err := template.ParseFiles("templates/index.html")
		if err != nil {
			c.String(http.StatusInternalServerError, "Error loading template")
			return
		}
		tmpl.Execute(c.Writer, nil)
	})

	// Endpoint untuk menerima data dari transmitter
	r.POST("/api/measurements", func(c *gin.Context) {
		var data struct {
			Unit      string `json:"unit"`
			DustLevel int    `json:"dust_level"`
		}

		if err := c.ShouldBindJSON(&data); err != nil {
			c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
			return
		}

		// Insert data ke tabel measurements
		_, err := db.ExecContext(context.Background(), "INSERT INTO measurements (unit, dust_level) VALUES ($1, $2)", data.Unit, data.DustLevel)
		if err != nil {
			log.Printf("Failed to insert data: %v", err)
			c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to insert data"})
			return
		}

		c.JSON(http.StatusOK, gin.H{"message": "Data inserted successfully"})
	})

	// Endpoint untuk menampilkan data
	r.GET("/api/measurements", func(c *gin.Context) {
		rows, err := db.QueryContext(context.Background(), "SELECT id, unit, dust_level, timestamp FROM measurements")
		if err != nil {
			log.Printf("Failed to fetch data: %v", err)
			c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to fetch data"})
			return
		}
		defer rows.Close()

		var measurements []gin.H
		for rows.Next() {
			var id int
			var unit string
			var dustLevel int
			var timestamp sql.NullTime

			// Scan hasil query
			if err := rows.Scan(&id, &unit, &dustLevel, &timestamp); err != nil {
				log.Printf("Failed to scan row: %v", err)
				c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to read data"})
				return
			}

			measurements = append(measurements, gin.H{
				"id":         id,
				"unit":       unit,
				"dust_level": dustLevel,
				"timestamp":  timestamp.Time,
			})
		}

		// Jika ada error saat iterasi rows
		if err := rows.Err(); err != nil {
			log.Printf("Error during row iteration: %v", err)
			c.JSON(http.StatusInternalServerError, gin.H{"error": "Error fetching data"})
			return
		}

		c.JSON(http.StatusOK, measurements)
	})

	// Jalankan server pada port 8080
	if err := r.Run(":8080"); err != nil {
		log.Fatalf("Failed to run server: %v", err)
	}
}
