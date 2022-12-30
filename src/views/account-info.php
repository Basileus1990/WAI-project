<?php if ($model['user'] !== null) : ?>
    <div class="account-info">
        <span>Zalogowany jako: <?= $model['user']['login'] ?></span>
        <form action="konto" method="post">
            <input type="text" name="logreg" hidden value="logout">
            <button type="submit">Wyloguj</button>
        </form>
    </div>
<?php endif ?>