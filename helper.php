<?php

// ============================================================
// EMIT_MARKER – emituje TOCMARKER div uvnitř komponenty
// Volá se jako první child hlavního wrapper divu.
// Aktivní jen pokud je $GLOBALS['display_marker'] = true.
// ============================================================
function EMIT_MARKER(): void
{
    if (!empty($GLOBALS['display_marker']) && isset($GLOBALS['_marker'])) {
        $id = $GLOBALS['_marker'];
        unset($GLOBALS['_marker']);
        echo '<div style="font-size:8px;color:#000;line-height:1;margin:0;padding:0;">TOCMARKER_' . $id . '</div>';
    }
}
