<main class="admin-panel">
    <h2 class="admin-title">Panneau d'administration des utilisateurs</h2>

    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['id']) ?></td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td>
                        <form action="/admin/user/update-role" method="post" class="role-form">
                            <input type="hidden" name="user-id" value="<?= $user['id'] ?>">
                            <select name="role">
                                <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>Utilisateur</option>
                                <option value="creator" <?= $user['role'] === 'creator' ? 'selected' : '' ?>>Créateur</option>
                                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                            </select>
                            <button type="submit" class="button-glow">Modifier</button>
                        </form>
                    </td>
                    <td>
                        <form action="/admin/user/delete-user" method="post" onsubmit="return confirm('Supprimer cet utilisateur ?');">
                            <input type="hidden" name="user-id" value="<?= $user['id'] ?>">
                            <button type="submit" class="button-delete">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>