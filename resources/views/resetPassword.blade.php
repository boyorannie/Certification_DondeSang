<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation du mot de passe</title>
</head>
<body>
    <h1>Réinitialisation du mot de passe</h1>
    <form action="{{route('reset.password.post')}}" method="post">
@csrf
        <label for="new_password">Nouveau mot de passe :</label>
        <input type="password" id="new_password" name="password" required>
        <br>
        <input type="hidden"  name="token" value="{{$token}}">
        <label for="confirm_password">Confirmer le mot de passe :</label>
        <input type="password" id="confirm_password" name="password_confirmation" required>
        <br>
        <input type="submit" value="Réinitialiser le mot de passe">
    </form>
</body>
</html>
