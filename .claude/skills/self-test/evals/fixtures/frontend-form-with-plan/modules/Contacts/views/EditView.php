<?php
/**
 * Contacts EditView — formularz edycji kontaktu
 */
?>
<form id="ContactsEditView" method="post" action="index.php">
    <input type="hidden" name="module" value="Contacts">
    <input type="hidden" name="action" value="Save">
    <input type="hidden" name="record" value="<?= htmlspecialchars($bean->id) ?>">

    <table class="edit-view">
        <tr>
            <td class="label">Imię</td>
            <td><input type="text" name="first_name" value="<?= htmlspecialchars($bean->first_name) ?>"></td>
            <td class="label">Nazwisko</td>
            <td><input type="text" name="last_name" value="<?= htmlspecialchars($bean->last_name) ?>"></td>
        </tr>
        <tr>
            <td class="label">Email</td>
            <td colspan="3">
                <input type="email" name="email1" id="email1" value="<?= htmlspecialchars($bean->email1) ?>">
            </td>
        </tr>
        <tr>
            <td class="label">Status</td>
            <td>
                <select name="status">
                    <option value="Active" <?= $bean->status === 'Active' ? 'selected' : '' ?>>Aktywny</option>
                    <option value="Inactive" <?= $bean->status === 'Inactive' ? 'selected' : '' ?>>Nieaktywny</option>
                </select>
            </td>
        </tr>
    </table>

    <button type="submit">Zapisz</button>
</form>
