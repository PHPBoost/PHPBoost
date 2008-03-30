#!/usr/bin/env python
# -*- coding: utf-8 -*-

import os, sys

def recurse(current_path, parent_path='', recursive=False):
    """
    Execute la commande spécifiée avant ou après avoir parcourus les
    sous répertoires suivant l'option de parcours 'order'
    """
    nbLines = 0
    if sys.platform == 'win32':
        current_path = (parent_path + '\\' + current_path).replace('\\\\', '\\')
    else:
        current_path = (parent_path + '/' + current_path).replace('//', '/')
    os.chdir(current_path)
    
    list_items = os.listdir(current_path)
    
    for item in list_items:
        if os.path.isdir(item): # Compte le nombre de ligne dans les sous-dossiers
            if recursive:
                nbLines += recurse(item, current_path, recursive)
                os.chdir(current_path)
        else: # Compte le nombre de ligne du fichier
            fic = open(item, 'r')
            ficNbLines = fic.readlines().__len__() - 1
            nbLines += ficNbLines
            fic.close()
    
    if nbLines > 0:
        return nbLines
    else:
        return 0

def init(path='', args=[]):
    """
    Initialise la récursion en préparant la commande ainsi que l'ordre
    d'exécution du parcours.
    """
    args = args[1:]
    go_recursive = False
    if args.__len__() == 1:
        path += '/' + args[0]
    elif args.__len__() == 2:
        if args[0] == '-r' or args[0] == '-R': go_recursive = True
        path += '/' + args[1]
    
    print "Nombre de lignes : " + recurse(path, recursive=go_recursive).__str__()

if __name__ == '__main__':
    init(os.getcwd(), sys.argv)
