<h2>Créer une classe</h2>

<form method="POST" action="/Models/classRoom.php">
    <label>Nom de la classe</label>
    <input type="text" name="name" required>

    <label>Description</label>
    <textarea name="description"></textarea>

    <button type="submit">Créer</button>
</form>