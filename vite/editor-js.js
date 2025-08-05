// editor-js.js - Version corrigée

import edjsHTML from "editorjs-html";
import DOMPurify from 'dompurify';

const edjsParser = edjsHTML();

let EditorJS, Header, List, ImageTool, Quote, Marker, CodeTool, Embed, Table;

console.log('EditorJS bundle dynamique chargé !');
const editorWidget = document.querySelector('.editorjs');

/**
 * Fonction pour uploader une image depuis un fichier.
 * @param {File} file - Le fichier image à uploader
 * @returns {Promise<Object>} Promise qui résout avec l'objet de réponse EditorJS
 */
export function editorJSImageByFile(file) {
    // Recherche de l'id de l'article par une nouvelle méthode
    const params = new URLSearchParams(window.location.search);
    const articleId = params.get('id');

    console.log("Hey coucou");

    const formData = new FormData();
    formData.append('article-image-content', 'true');
    formData.append('article-id', articleId);
    formData.append('image', file);

    return fetch('/api/upload', {
        method: 'POST',
        body: formData,
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(result => {
            return {
                success: 1,
                file: {
                    url: `${result.filename}`,
                }
            };
        })
        .catch(error => {
            console.error('Upload error:', error);
            return {
                success: 0,
                error: error.message
            };
        });
}

/**
 * Fonction pour uploader une image depuis une URL.
 * @param {string} url - L'URL de l'image
 * @returns {Promise<Object>} Promise qui résout avec l'objet de réponse EditorJS
 */
// A NE PAS UTILISER POUR l'INSTANT
export function editorJSImageByUrl(url) {
    return fetch('/api/upload-by-url', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ url: url }),
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(result => {
            return {
                success: 1,
                file: {
                    url: `/uploads/${result.filename}`,
                }
            };
        })
        .catch(error => {
            console.error('Upload by URL error:', error);
            return {
                success: 0,
                error: error.message
            };
        });
}

// Expose les fonctions globalement
window.editorJSImageByFile = editorJSImageByFile;
window.editorJSImageByUrl = editorJSImageByUrl;

window.EditorJSToolsReady = (async () => {
    if (editorWidget) {
        console.log('EditorJS is present on the page.');

        try {
            // Import dynamique des modules EditorJS
            [
                { default: EditorJS },
                { default: Header },
                { default: List },
                { default: ImageTool },
                { default: Quote },
                { default: Marker },
                { default: CodeTool },
                { default: Embed },
                { default: Table }
            ] = await Promise.all([
                import('@editorjs/editorjs'),
                import('@editorjs/header'),
                import('@editorjs/list'),
                import('@editorjs/image'),
                import('@editorjs/quote'),
                import('@editorjs/marker'),
                import('@editorjs/code'),
                import('@editorjs/embed'),
                import('@editorjs/table')
            ]);

            console.log("EditorJS modules loaded successfully.");
            console.log("ImageTool:", ImageTool);
            console.log("Upload function type:", typeof window.editorJSImageByFile);

        } catch (error) {
            console.error("Error loading EditorJS modules:", error);
            throw error;
        }
    }
})();

/**
 * Fonction pour créer une nouvelle instance de EditorJS
 * @param {HTMLElement} holder - L'élément HTML conteneur
 * @param {Object} data - Les données à afficher
 * @param {boolean} readOnly - Mode lecture seule
 * @returns {Promise<EditorJS>} Instance d'EditorJS
 */
export async function newEditorJSInstance(holder, data = {}, readOnly = false) {
    if (!holder) {
        throw new Error("Le paramètre 'holder' est requis pour créer une nouvelle instance de EditorJS.");
    }

    // Attendre que les outils soient chargés
    await window.EditorJSToolsReady;

    // Détruire l'instance précédente si elle existe
    if (window.editorJSInstance) {
        try {
            await window.editorJSInstance.isReady;
            await window.editorJSInstance.destroy();
        } catch (error) {
            console.warn("Error destroying previous editor instance:", error);
        }
    }

    const editor = createEditorJSConfig(holder, data, readOnly);
    window.editorJSInstance = editor;

    return editor;
}

/**
 * Fonction pour créer la configuration d'EditorJS
 * @param {HTMLElement} holder - L'élément conteneur
 * @param {Object} data - Les données
 * @param {boolean} readOnly - Mode lecture seule
 * @returns {EditorJS} Instance d'EditorJS
 */
function createEditorJSConfig(holder, data = {}, readOnly = false) {
    if (!holder) {
        throw new Error("Le paramètre 'holder' est requis pour créer une nouvelle instance de EditorJS.");
    }

    console.log('Creating EditorJS with ImageTool:', ImageTool);
    console.log('Upload functions available:', {
        byFile: typeof window.editorJSImageByFile,
        byUrl: typeof window.editorJSImageByUrl
    });

    const config = {
        holder: holder,
        data: data || {},
        readOnly: readOnly,
        placeholder: readOnly ? '' : 'Commencez à écrire votre article...',
        tools: {
            header: {
                class: Header,
                config: {
                    placeholder: 'Entrez un titre...',
                    levels: [2, 3, 4],
                    defaultLevel: 2
                }
            },
            list: {
                class: List,
                inlineToolbar: true,
                config: {
                    defaultStyle: 'unordered'
                }
            },
            quote: {
                class: Quote,
                inlineToolbar: true,
                shortcut: 'CMD+SHIFT+O',
                config: {
                    quotePlaceholder: 'Entrez une citation',
                    captionPlaceholder: 'Auteur de la citation',
                }
            },
            marker: {
                class: Marker,
                shortcut: 'CMD+SHIFT+M'
            },
            code: {
                class: CodeTool,
                shortcut: 'CMD+SHIFT+C'
            },
            embed: {
                class: Embed,
                config: {
                    services: {
                        youtube: true,
                        coub: true,
                        codepen: true,
                        twitter: true,
                        instagram: true
                    }
                }
            },
            table: {
                class: Table,
                inlineToolbar: true,
                config: {
                    rows: 2,
                    cols: 3,
                }
            }
        }
    };

    // Ajouter l'outil image seulement s'il n'est pas en mode lecture seule
    if (!readOnly && ImageTool) {
        config.tools.image = {
            class: ImageTool,
            config: {
                uploader: {
                    uploadByFile: window.editorJSImageByFile,
                    uploadByUrl: window.editorJSImageByUrl,
                },
                captionPlaceholder: 'Légende de l\'image',
                buttonContent: 'Sélectionner une image',
                types: 'image/*',
                additionalRequestData: {},
                additionalRequestHeaders: {},
                field: 'image',
                actions: [
                    {
                        name: 'new_button',
                        icon: '<svg>...</svg>',
                        title: 'Nouvelle image',
                        toggle: true,
                        action: (name) => {
                            console.log(`${name} button clicked`);
                        }
                    }
                ]
            }
        };
    }

    console.log('EditorJS config:', config);

    try {
        const editor = new EditorJS(config);

        // Gérer les erreurs de l'éditeur
        editor.isReady
            .then(() => {
                console.log('EditorJS is ready to work!');
            })
            .catch((reason) => {
                console.error('EditorJS initialization failed:', reason);
            });

        return editor;
    } catch (error) {
        console.error('Error creating EditorJS instance:', error);
        throw error;
    }
}

/**
 * Fonction pour convertir le contenu EditorJS en HTML
 * @param {Object} editorjs_clean_data - Données JSON d'EditorJS
 * @returns {string} HTML généré
 */
export async function getEditorHtml(editorjs_clean_data) {
    if (!editorjs_clean_data || !editorjs_clean_data.blocks) {
        return '';
    }

    try {
        const htmlBlocks = edjsParser.parse(editorjs_clean_data);
        return htmlBlocks;
    } catch (error) {
        console.error('Error parsing EditorJS data to HTML:', error);
        return '';
    }
}

/**
 * Fonction pour purifier le HTML
 * @param {string} htmlString - HTML à purifier
 * @returns {string} HTML purifié
 */
export function purifyHTML(htmlString) {
    if (!htmlString) return '';

    return DOMPurify.sanitize(htmlString, {
        ALLOWED_TAGS: ['b', 'i', 'em', 'strong', 'a', 'p', 'br', 'ul', 'ol', 'li', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'blockquote', 'code', 'img', 'figure', 'figcaption'],
        ALLOWED_ATTR: ['href', 'target', 'src', 'alt', 'width', 'height', 'class']
    });
}

// Exposer les fonctions globalement
window.getEditorHtml = getEditorHtml;
window.purifyHTML = purifyHTML;
window.newEditorJSInstance = newEditorJSInstance;