<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @include('emails.project.style.document-style')
</head>
<body>
    <div class="email-body">
        <div class="email-body-header">
            <h2>[[subject]]</h2>
        </div>
        <div class="email-body-main">
            [[body-main]]
        </div>
        <div class="email-body-footer">
            <p>&copy; 2024 Your Company. Minden jog fenntartva.</p>
            <p><a href="#">Leiratkozás</a></p>
        </div>
    </div>
</body>
</html>
