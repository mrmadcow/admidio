<?php
/******************************************************************************
 * Datenkonvertierung fuer die Version 1.4
 *
 * Copyright    : (c) 2004 - 2006 The Admidio Team
 * Homepage     : http://www.admidio.org
 * Module-Owner : Markus Fassbender
 *
 ******************************************************************************
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 *****************************************************************************/

define("TBL_ROLE_CATEGORIES",   $g_tbl_praefix. "_role_categories");

// Categorien-Tabelle kopieren
$sql = "INSERT INTO ". TBL_CATEGORIES. " (cat_id, cat_org_id, cat_type, cat_name, cat_hidden)
                        SELECT rlc_id, org_id, 'ROL', rlc_name, rlc_locked
                          FROM ". TBL_ROLE_CATEGORIES. "
                          LEFT JOIN ". TBL_ORGANIZATIONS. "
                            ON rlc_org_shortname = org_shortname ";
$result = mysql_query($sql, $connection);
if(!$result) showError(mysql_error());

$sql = "drop table ". TBL_ROLE_CATEGORIES;
$result = mysql_query($sql, $connection);
if(!$result) showError(mysql_error());

// Orga-Felder in adm_preferences umwandeln
$sql = "SELECT * FROM ". TBL_ORGANIZATIONS;
$result_orga = mysql_query($sql, $connection);
if(!$result_orga) showError(mysql_error());

while($row_orga = mysql_fetch_object($result_orga))
{
    $sql = "INSERT INTO ". TBL_PREFERENCES. " (prf_org_id, prf_name, prf_value)
            VALUES ($row_orga->org_id, 'registration_mode', '1')";
    $result = mysql_query($sql, $connection);
    if(!$result) showError(mysql_error());

    $sql = "INSERT INTO ". TBL_PREFERENCES. " (prf_org_id, prf_name, prf_value)
            VALUES ($row_orga->org_id, 'logout_minutes', '30')";
    $result = mysql_query($sql, $connection);
    if(!$result) showError(mysql_error());

    $sql = "INSERT INTO ". TBL_PREFERENCES. " (prf_org_id, prf_name, prf_value)
            VALUES ($row_orga->org_id, 'photo_thumbs_column', '5')";
    $result = mysql_query($sql, $connection);
    if(!$result) showError(mysql_error());

    $sql = "INSERT INTO ". TBL_PREFERENCES. " (prf_org_id, prf_name, prf_value)
            VALUES ($row_orga->org_id, 'photo_thumbs_row', '5')";
    $result = mysql_query($sql, $connection);
    if(!$result) showError(mysql_error());

    $sql = "INSERT INTO ". TBL_PREFERENCES. " (prf_org_id, prf_name, prf_value)
            VALUES ($row_orga->org_id, 'photo_thumbs_scale', '100')";
    $result = mysql_query($sql, $connection);
    if(!$result) showError(mysql_error());

    $sql = "INSERT INTO ". TBL_PREFERENCES. " (prf_org_id, prf_name, prf_value)
            VALUES ($row_orga->org_id, 'photo_show_width', '500')";
    $result = mysql_query($sql, $connection);
    if(!$result) showError(mysql_error());

    $sql = "INSERT INTO ". TBL_PREFERENCES. " (prf_org_id, prf_name, prf_value)
            VALUES ($row_orga->org_id, 'photo_show_height', '380')";
    $result = mysql_query($sql, $connection);
    if(!$result) showError(mysql_error());

    $sql = "INSERT INTO ". TBL_PREFERENCES. " (prf_org_id, prf_name, prf_value)
            VALUES ($row_orga->org_id, 'photo_image_text', '1')";
    $result = mysql_query($sql, $connection);
    if(!$result) showError(mysql_error());

    $sql = "INSERT INTO ". TBL_PREFERENCES. " (prf_org_id, prf_name, prf_value)
            VALUES ($row_orga->org_id, 'photo_preview_scale', '100')";
    $result = mysql_query($sql, $connection);
    if(!$result) showError(mysql_error());

    $sql = "INSERT INTO ". TBL_PREFERENCES. " (prf_org_id, prf_name, prf_value)
            VALUES ($row_orga->org_id, 'enable_mail_captcha', '1')";
    $result = mysql_query($sql, $connection);
    if(!$result) showError(mysql_error());

    $sql = "INSERT INTO ". TBL_PREFERENCES. " (prf_org_id, prf_name, prf_value)
            VALUES ($row_orga->org_id, 'enable_guestbook_captcha', '1')";
    $result = mysql_query($sql, $connection);
    if(!$result) showError(mysql_error());

    $sql = "INSERT INTO ". TBL_PREFERENCES. " (prf_org_id, prf_name, prf_value)
            VALUES ($row_orga->org_id, 'flooding_protection_time', '180')";
    $result = mysql_query($sql, $connection);
    if(!$result) showError(mysql_error());

    // Alle Links bekommen erst einmal die neue Kategorie "Allgemein"

    $sql = "INSERT INTO ". TBL_CATEGORIES. " (cat_org_id, cat_type, cat_name, cat_hidden)
                                      VALUES ($row_orga->org_id, 'LNK', 'Allgemein', 0) ";
    $result = mysql_query($sql, $connection);
    if(!$result) showError(mysql_error());

    $cat_id = mysql_insert_id($connection);

    $sql = "UPDATE ". TBL_LINKS. " SET lnk_cat_id = $cat_id
             WHERE lnk_org_id = $row_orga->org_id ";
    $result = mysql_query($sql, $connection);
    if(!$result) showError(mysql_error());
}

// Referenz zw. Rollen und Kategorie wiederherstellen
$sql = "alter table ". TBL_ROLES. " add constraint ". $g_tbl_praefix. "_FK_ROL_CAT foreign key (rol_cat_id)
        references ". TBL_CATEGORIES. " (cat_id) on delete restrict on update restrict ";
$result = mysql_query($sql, $connection);
if(!$result) showError(mysql_error());

// Referenz zw. Rollen und Kategorie wiederherstellen
$sql = "alter table ". TBL_LINKS. " add constraint ". $g_tbl_praefix. "_FK_LNK_CAT foreign key (lnk_cat_id)
        references ". TBL_CATEGORIES. " (cat_id) on delete restrict on update restrict ";
$result = mysql_query($sql, $connection);
if(!$result) showError(mysql_error());

?>