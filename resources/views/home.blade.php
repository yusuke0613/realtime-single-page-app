<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900|Material+Icons" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">
        <title>Laravel</title>
        <link rel="stylesheet" href="{{asset('css/app.css')}}">
        
    </head>
    <body>
        <div id="app">
          <v-app>
          <app-home></app-home>
          </v-app>
        </div>
        
        <script src="{{asset('js/app.js')}}"></script>
    </body>
</html>
