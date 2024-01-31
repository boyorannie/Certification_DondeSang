<div class="text">
    <h4>Voici votre lien de Réinitialisation</h4>
    <hr>
    <p>utiliser ce lien pour réinitialiser votre mot de passe</p>
    <h4><a href="{{ route('reset.password.get', $token) }}">lien de rénitialisation </a></h4>
    <h6>Ce code est valide pour 3 heures.</h6>
</div>
</div>
</body>