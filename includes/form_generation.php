<?php

/*##################################################
 *                              modules.class.php
 *                            -------------------
 *   begin                : January 15, 2008
 *   copyright            : (C) 2008 Rouchon LoÃ¯c
 *   email                : xhorn37@yahoo.fr
 *
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

error_reporting(E_ALL);

$TYPES = Array (
        'input' => Array ('text','password','hidden','radio'),
        'textarea' => null,
        'select' => null
    );

print_r($TYPES);

function GenerateFormFields (&$args)
/**
 *  Génère les champs du formulaires à partir de la description fourni dans
 *  $args.
 */
{
    
    
    $formFields = '';
    
    foreach ($args as $arg => $value)
    {
        $argType = $value[0];
        $formType = $value[1];
        
        switch ($formType)
        {
            case 'input':
                if ($value[2] != 'radio')
                { $formFields .= '<input type="'.$value[2].'" id="'.$arg.'" value="'.$value[3].'" />'; }
                else
                {
                    $i = 0;
                    foreach ($value[3] as $radioValue)
                    {
                        $formFields .= '<input type="radio" name="'.$arg.'" ';
                        if ($i == 0)
                        {
                            $formFields .= 'id="'.$arg.'" ';
                            $i = 1;
                        }
                        $formFields .= 'value="'.$radioValue.'" />';
                    }
                }
                break;
            case 'textarea':
                $formFields .= '<textarea id="'.$arg.'">'.$value[1].'</textarea>';
                break;
            case 'select':
                break;
        }
    }
    
    return $formFields;
}

 
$Args = Array (
    'search' => Array ('string', 'input', 'text', 'valeur par défault'),
    'time' => Array ('int', 'select', Array('Tout' => 30000, '1 jour' => 1)),
    'idcat' => Array ('int', 'select', Array('Tout' => 0, 'Développement' => 1)),
    'where' => Array ('int', 'input', 'radio', Array('Tout', 'Titre', 'Contenu'))
);

echo '<hr />'.htmlentities(GenerateFormFields($Args)).'<hr />';
echo (GenerateFormFields($Args)).'<hr />';


?>