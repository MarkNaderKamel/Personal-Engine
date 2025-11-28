<?php

namespace App\Core;

class Helper
{
    public static function csrfField()
    {
        return '<input type="hidden" name="csrf_token" value="' . Security::generateCSRFToken() . '">';
    }

    public static function deleteButton($url, $text = 'Delete', $class = 'btn btn-sm btn-danger')
    {
        $csrf = Security::generateCSRFToken();
        return sprintf(
            '<form method="POST" action="%s" style="display:inline;" onsubmit="return confirm(\'Are you sure?\')">
                <input type="hidden" name="csrf_token" value="%s">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="%s">%s</button>
            </form>',
            htmlspecialchars($url),
            $csrf,
            htmlspecialchars($class),
            htmlspecialchars($text)
        );
    }
}
