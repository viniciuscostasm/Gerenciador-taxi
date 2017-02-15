<?php

/**
 * Copyright (C) 2011 Salvador Torres e Luiz Eduardo Alves Santos
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

/**
 * Classe mãe da biblioteca
 */
class Genesis {

    function Genesis() {

        // carregar bibliotecas
        require_once(ROOT_GENESIS . "inc/functions.class.php");
        require_once(ROOT_GENESIS . "inc/exceptions/exceptions.php");
        require_once(ROOT_GENESIS . "inc/database.class.php");
        require_once(ROOT_GENESIS . "inc/filter.class.php");
        require_once(ROOT_GENESIS . "inc/header.lib.php");
        require_once(ROOT_GENESIS . "inc/header.parent.class.php");
        require_once(ROOT_GENESIS . "inc/footer.parent.class.php");
        require_once(ROOT_GENESIS . "inc/form.parent.class.php");
        require_once(ROOT_GENESIS . "inc/paginate.class.php");
        require_once(ROOT_GENESIS . "inc/report.pdf.class.php");
        require_once(ROOT_GENESIS . "inc/report.excel.class.php");
        require_once(ROOT_GENESIS . "inc/report.fpdf.class.php");

        return true;
    }

}

?>