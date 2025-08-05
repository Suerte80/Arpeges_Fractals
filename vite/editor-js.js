import edjsHTML from "editorjs-html"; // syntaxe ESModule
import DOMPurify from 'dompurify'; // syntaxe ESModule

const edjsParser = edjsHTML(); // tu peux placer ça en haut si tu veux réutiliser

let EditorJS, Header, List, ImageTool, Quote, Marker, CodeTool, Embed, Table;

console.log('EditorJS bundle dynamique chargé !');
const editorWidget = document.querySelector('.editorjs');

window.EditorJSToolsReady = (async () => {
    if (editorWidget) {

        console.log('EditorJS is present on the page.');

        /*
         * On utilise l'import dynamique pour charger que lorsqu'on a l'éditeur dans la page !
         * Avant cela j'utilisé l'import statique mais ça chargeait inutilement les dépendances.
         */
        [
            { default: EditorJS },
            { default: Header },
            { default: List },
            // TODO problème avec ImageTool !!!!
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

        //console.log("EditorJS and its tools loaded successfully.", window.testModules);

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

        console.log("EditorJS instance created successfully.", editor);

        window.editorJSInstance = editor; // On stocke l'instance de l'éditeur dans une variable globale pour y accéder plus tard.

        console.log("EditorJS initialized successfully.");
    }
})();

/**
 * Fonction asynchrone pour convertir le contenu de EditorJS en HTML.
 * @param {JSON} editorjs_clean_data Object json venant de EditorJS.save()
 * @returns Une string qui représente le HTML généré par EditorJS
 */
export async function getEditorHtml(editorjs_clean_data) {
    // editorjs_clean_data = JSON venant de EditorJS.save()
    const htmlBlocks = edjsParser.parse(editorjs_clean_data); // C'est une string pas un tableau
    return htmlBlocks;
}

/**
 * Fonction pour purifier le HTML généré par EditorJS.
 * @param {String} htmlString String représentant du HTML à purifier.
 * @returns {String} Une string représentant le HTML purifié.
 */
export function purifyHTML(htmlString) {
    return DOMPurify.sanitize(htmlString, {
        ALLOWED_TAGS: ['b', 'i', 'em', 'strong', 'a', 'p', 'br', 'ul', 'ol', 'li', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'blockquote', 'code'],
        ALLOWED_ATTR: ['href', 'target']
    });
}

/**
 * Fonction pour créer une nouvelle instance de EditorJS tout en détruisant l'ancienne instance si elle existe.
 * @param {HTMLElement} holder La ou va être l'éditeur.
 * @param {JSON} data Les données qui seront afficher.
 * @param {bool} readOnly Si l'éditeur est en lecture seule ou non.
 * @throws {Error} Si le paramètre 'holder' n'est pas fourni.
 * @returns A new editorJS instance.
 */
export async function newEditorJSInstance(holder, data, readOnly = false) {
    if (!holder) {
        throw new Error("Le paramètre 'holder' est requis pour créer une nouvelle instance de EditorJS.");
    }

    if (window.editorJSInstance) {
        await window.editorJSInstance.isReady;
        await window.editorJSInstance.destroy();
    }

    const editor = new EditorJS({
        holder: holder,
        data: data,
        readOnly: readOnly,
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
}

// Expose la fonction si tu veux l'appeler depuis un script inline
window.getEditorHtml = getEditorHtml;

// Expose la fonction de purification
window.purifyHTML = purifyHTML;

// Expose la fonction pour créer une nouvelle instance de EditorJS
window.newEditorJSInstance = newEditorJSInstance;