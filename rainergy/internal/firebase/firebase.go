package firebase

import (
	"context"
	"log"
	"os"

	firebase "firebase.google.com/go/v4"
	"google.golang.org/api/option"
)

var App *firebase.App

func InitFirebase() {
	ctx := context.Background()

	// Ambil path ke file serviceAccountKey.json
	creds := option.WithCredentialsFile(os.Getenv("GOOGLE_APPLICATION_CREDENTIALS"))

	app, err := firebase.NewApp(ctx, nil, creds)
	if err != nil {
		log.Fatalf("error initializing firebase app: %v", err)
	}

	App = app
}
