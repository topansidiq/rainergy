package firebase

import (
	"context"
	"log"

	db "firebase.google.com/go/v4/db"
)

func GetDBClient(ctx context.Context) *db.Client {
	if App == nil {
		log.Fatal("Firebase App is not initialized")
	}

	client, err := App.Database(ctx)
	if err != nil {
		log.Fatalf("error getting DB client: %v", err)
	}

	return client
}
