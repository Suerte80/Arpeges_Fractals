console.log('EditorJS bundle dynamique chargé !');
const editorWidget = document.querySelector('.editorjs');

if (editorWidget) {

    console.log('EditorJS is present on the page.');

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

    window.editorJSInstance = editor; // On stocke l'instance de l'éditeur dans une variable globale pour y accéder plus tard.

    console.log("EditorJS initialized successfully.");
}
