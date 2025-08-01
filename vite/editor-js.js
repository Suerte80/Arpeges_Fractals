document.addEventListener('DOMContentLoaded', async () => {
    const editorWidget = document.querySelector('.editorjs');

    if (editorWidget) {

        // Ici on importe dynamiquement le fichier js editorJS
        // Le nom a été donné grace au php dans le fichier head_view.php
        await import(editorJsBundle);

        /*
         * On utilise l'import dynamique pour charger que lorsqu'on a l'éditeur dans la page !
         * Avant cela j'utilisé l'import statique mais ça chargeait inutilement les dépendances.
         */
        const [
            { default: EditorJS },
            { default: Header },
            { default: List },
            { default: ImageTool },
            { default: Quote },
            { default: Marker },
            { default: CodeTool },
            { default: Embed },
            { default: Table }
        ] = await Promise.all([ // mot clé await permet d'attendre que la promise soit fini.
            import('@editorjs/editorjs'), // Alors les @ permet de dire a vite de chercher dans node_modules
            import('@editorjs/header'),
            import('@editorjs/list'),
            import('@editorjs/image'),
            import('@editorjs/quote'),
            import('@editorjs/marker'),
            import('@editorjs/code'),
            import('@editorjs/embed'),
            import('@editorjs/table')
        ]);

        const editor = new EditorJS({
            holder: editorWidget,
            tools: {
                header: Header,
                list: List,
                image: ImageTool,
                quote: Quote,
                marker: Marker,
                code: CodeTool,
                embed: Embed,
                table: Table,
            }
        });
    }
});
