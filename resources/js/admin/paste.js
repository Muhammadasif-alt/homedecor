/**
 * "Paste article" with photos: an article copied from a WordPress post, Google Docs or an AI
 * writing tool arrives on the clipboard as HTML with its images. The paste box turns that HTML
 * into the brief format the server understands (# title, ## sections, [IMAGE: …] notes) and
 * uploads every pasted image to the media library, so each note already has its photo.
 */
const meta = (name) => document.querySelector(`meta[name="${name}"]`)?.content ?? '';

const SKIP = new Set(['SCRIPT', 'STYLE', 'BUTTON', 'SVG', 'NOSCRIPT', 'TEMPLATE', 'META', 'LINK', 'IFRAME', 'VIDEO', 'AUDIO']);
const BLOCKS = new Set(['P', 'DIV', 'SECTION', 'ARTICLE', 'MAIN', 'HEADER', 'FOOTER', 'ASIDE', 'NAV', 'H1', 'H2', 'H3', 'H4', 'H5', 'H6',
    'UL', 'OL', 'LI', 'TABLE', 'TR', 'BLOCKQUOTE', 'FIGURE', 'IMG', 'HR', 'PICTURE']);

const clean = (text) => text.replace(/ /g, ' ').replace(/[ \t\r\n]+/g, ' ');

const isBold = (el) => {
    const weight = el.style?.fontWeight;
    if (weight) return weight === 'bold' || Number(weight) >= 600;
    return el.tagName === 'STRONG' || el.tagName === 'B';
};

const isItalic = (el) => (el.style?.fontStyle ? el.style.fontStyle === 'italic' : el.tagName === 'EM' || el.tagName === 'I');

function inline(node) {
    let out = '';
    node.childNodes.forEach((child) => {
        if (child.nodeType === Node.TEXT_NODE) {
            out += clean(child.textContent);
            return;
        }
        if (child.nodeType !== Node.ELEMENT_NODE || SKIP.has(child.tagName)) return;
        if (child.tagName === 'BR') {
            out += '\n';
            return;
        }
        let text = inline(child);
        if (!text.trim()) {
            out += text;
            return;
        }
        if (child.tagName === 'A' && /^https?:/i.test(child.getAttribute('href') ?? '')) {
            text = `[${text.trim()}](${child.getAttribute('href')})`;
        } else if (child.tagName === 'CODE') {
            text = `\`${text}\``;
        }
        if (isBold(child)) text = `**${text.trim()}**`;
        else if (isItalic(child)) text = `*${text.trim()}*`;
        out += text;
    });
    return out;
}

const hasBlocks = (el) => [...el.children].some((child) => BLOCKS.has(child.tagName) || hasBlocks(child));

/** Real writing, as opposed to a short label or button text next to a photo. */
const hasText = (el) => {
    const copy = el.cloneNode(true);
    copy.querySelectorAll([...SKIP].join(',')).forEach((node) => node.remove());
    return clean(copy.textContent).trim().length >= 40;
};

/** @returns {{ text: string, images: Array<{ src: string, alt: string, caption: string }> }} */
export function htmlToBrief(html) {
    const doc = new DOMParser().parseFromString(html, 'text/html');
    const blocks = [];
    const images = [];
    const used = new Map();

    const addImage = (img, caption = '') => {
        const src = img.currentSrc || img.getAttribute('src') || img.getAttribute('data-src') || '';
        if (!src || /^data:image\/(gif|svg)/i.test(src) || (Number(img.getAttribute('width')) || 99) < 40) return;
        let alt = clean(caption || img.getAttribute('alt') || img.getAttribute('title') || '').trim().replace(/[[\]]/g, '') || `Photo ${images.length + 1}`;
        const seen = used.get(alt) ?? 0;
        used.set(alt, seen + 1);
        if (seen) alt = `${alt} (${seen + 1})`;
        images.push({ src, alt, caption: clean(caption).trim() });
        blocks.push(`[IMAGE: ${alt}]`);
    };

    const paragraph = (prefix, el) => {
        const text = inline(el).split('\n').map((line) => line.trim()).filter(Boolean).join('\n');
        if (text) blocks.push(prefix + text);
    };

    const walk = (node) => {
        [...node.childNodes].forEach((el) => {
            if (el.nodeType === Node.TEXT_NODE) {
                if (el.textContent.trim()) blocks.push(clean(el.textContent).trim());
                return;
            }
            if (el.nodeType !== Node.ELEMENT_NODE || SKIP.has(el.tagName)) return;

            switch (el.tagName) {
                case 'H1': return paragraph('# ', el);
                case 'H2': return paragraph('## ', el);
                case 'H3': case 'H4': case 'H5': case 'H6': return paragraph('### ', el);
                case 'HR': return blocks.push('***');
                case 'IMG': return addImage(el);
                case 'FIGURE': {
                    const caption = el.querySelector('figcaption')?.textContent ?? '';
                    el.querySelectorAll('img').forEach((img, i) => addImage(img, i === 0 ? caption : ''));
                    return;
                }
                case 'UL': case 'OL': {
                    const items = [...el.children].filter((li) => li.tagName === 'LI');
                    const lines = items.map((li) => inline(li).replace(/\s*\n\s*/g, ' ').trim())
                        .map((text, i) => text && (el.tagName === 'OL' ? `${i + 1}. ` : '* ') + text).filter(Boolean);
                    if (lines.length) blocks.push(lines.join('\n'));
                    items.forEach((li) => li.querySelectorAll('img').forEach((img) => addImage(img)));
                    return;
                }
                case 'BLOCKQUOTE': return paragraph('> ', el);
                case 'TABLE':
                    el.querySelectorAll('tr').forEach((tr) => {
                        const cells = [...tr.children].map((cell) => inline(cell).trim()).filter(Boolean);
                        if (cells.length) blocks.push(cells.join(' | '));
                    });
                    return;
                default:
                    // A photo frame from a writing tool: keep the photo, drop labels such as "WebP" or "Regenerate image".
                    if (el.querySelector('img') && !el.querySelector('h1,h2,h3,h4,h5,h6,p,li,blockquote,table') && !hasText(el)) {
                        el.querySelectorAll('img').forEach((img) => addImage(img));
                    } else if (el.querySelector('img') || hasBlocks(el)) {
                        walk(el);
                    } else {
                        paragraph('', el);
                    }
            }
        });
    };

    walk(doc.body);

    return { text: blocks.join('\n\n').replace(/\n{3,}/g, '\n\n').trim(), images };
}

/** Markdown copied from a chat tool: "![alt](https://…)" image links become image notes with photos to fetch. */
export function markdownImages(markdown) {
    const images = [];
    const text = (markdown ?? '').replace(/!\[([^\]]*)\]\(\s*<?((?:https?|data):[^\s)>]+)>?(?:\s+"[^"]*")?\s*\)/gi, (match, alt, src) => {
        const note = clean(alt).trim().replace(/[[\]]/g, '') || `Photo ${images.length + 1}`;
        images.push({ src, alt: note, caption: '' });
        return `\n\n[IMAGE: ${note}]\n\n`;
    });

    return { text: text.replace(/\n{3,}/g, '\n\n').trim(), images };
}

const extensionFor = (type) => ({ 'image/jpeg': 'jpg', 'image/png': 'png', 'image/webp': 'webp' })[type] ?? 'jpg';

async function sendJson(url, body) {
    const response = await fetch(url, {
        method: 'POST',
        body,
        headers: { Accept: 'application/json', 'X-CSRF-TOKEN': meta('csrf-token'), 'X-Requested-With': 'XMLHttpRequest' },
    });
    const json = await response.json().catch(() => ({}));
    if (!response.ok) {
        const errors = json.errors ? Object.values(json.errors).flat() : [json.message];
        throw new Error(errors.filter(Boolean).join(' ') || `Upload failed (${response.status}).`);
    }
    return Array.isArray(json.data) ? json.data[0] : json.data;
}

export const articlePaste = () => ({
    images: [],
    notice: '',

    get uploading() {
        return this.images.some((image) => image.status === 'uploading');
    },

    async paste(event) {
        const data = event.clipboardData;
        if (!data) return;

        const html = data.getData('text/html');
        const plain = data.getData('text/plain');
        const files = [...data.files].filter((file) => file.type.startsWith('image/'));
        let result = html ? htmlToBrief(html) : markdownImages(plain);

        // Plain text with no photos (a brief typed in the house format) pastes as normal.
        if (!result.images.length && !files.length && (!html || !result.text)) return;
        event.preventDefault();

        // Only a photo was copied ("Copy image"): it fills the next image note that has no photo yet.
        const photoOnly = !result.text.replace(/^\[IMAGE:.*\]$/gm, '').trim();
        if (photoOnly) {
            const photos = files.length
                ? files.map((file) => ({ src: '', file, caption: '' }))
                : result.images.map(({ src, caption }) => ({ src, caption, file: null }));
            return this.place(photos);
        }

        const textarea = this.$refs.text;
        const before = textarea.value.slice(0, textarea.selectionStart).trim();
        const after = textarea.value.slice(textarea.selectionEnd).trim();
        textarea.value = [before, result.text, after].filter(Boolean).join('\n\n');
        textarea.dispatchEvent(new Event('input', { bubbles: true }));

        // A copied single image arrives as both an <img> and a file; only then do the two line up.
        const filesMatch = files.length === result.images.length;
        await this.uploadAll(result.images.map((image, i) => ({ ...image, file: image.file ?? (filesMatch ? files[i] : null) })), 'Pasted the text and');
    },

    /** Photos chosen, dropped or pasted on their own go to the image notes still waiting for one, in order. */
    async place(photos) {
        const textarea = this.$refs.text;
        const added = photos.map((photo) => {
            let alt = this.nextOpenNote(photos);
            if (!alt) {
                alt = `Photo ${this.images.length + photos.indexOf(photo) + 1}`;
                textarea.value = [textarea.value.trim(), `[IMAGE: ${alt}]`].filter(Boolean).join('\n\n');
            }
            photo.alt = alt;
            return photo;
        });
        textarea.dispatchEvent(new Event('input', { bubbles: true }));
        await this.uploadAll(added, 'Adding');
    },

    addFiles(fileList) {
        const photos = [...(fileList ?? [])].filter((file) => file.type.startsWith('image/')).map((file) => ({ src: '', file, caption: '' }));
        if (photos.length) this.place(photos);
    },

    /** The first image note in the text without a photo, counting repeats of the same wording. */
    nextOpenNote(pending = []) {
        const taken = new Map();
        [...this.images, ...pending].forEach((image) => image.alt && taken.set(image.alt, (taken.get(image.alt) ?? 0) + 1));
        for (const [, note] of this.$refs.text.value.matchAll(/^\s*\[IMAGE:\s*(.*?)\]\s*$/gim)) {
            const count = taken.get(note) ?? 0;
            if (count === 0) return note;
            taken.set(note, count - 1);
        }
        return null;
    },

    async uploadAll(photos, verb) {
        const added = photos.map((photo) => ({ ...photo, status: 'uploading', media: null, error: '' }));
        this.images.push(...added);
        this.notice = added.length ? `${verb} ${added.length} photo(s). Adding them to the media library…` : 'Pasted the text. No photos came with it.';

        // Upload through the reactive copies so each thumbnail and hidden field updates as it lands.
        const queued = this.images.slice(this.images.length - added.length);
        for (const image of queued) {
            await this.upload(image);
        }
        if (added.length) {
            const failed = queued.filter((image) => image.status === 'failed').length;
            this.notice = failed
                ? `${added.length - failed} photo(s) added. ${failed} could not be added: right-click the photo, choose "Copy image" and paste it here, or add it on the next step.`
                : `All ${added.length} photo(s) added to the media library and placed in the article.`;
        }
    },

    async upload(image) {
        try {
            const file = await this.fileFor(image);
            let media;
            if (file) {
                const body = new FormData();
                body.append('files[]', file, file.name);
                body.append('alt', image.alt.slice(0, 255));
                media = await sendJson(meta('media-upload-url'), body);
            } else if (/^https?:/i.test(image.src)) {
                const body = new FormData();
                body.append('url', image.src);
                body.append('alt', image.alt.slice(0, 255));
                media = await sendJson(meta('media-import-url'), body);
            } else {
                throw new Error('This photo cannot be copied from that page. Save it and upload it on the next step.');
            }
            image.media = media;
            image.status = 'done';
        } catch (e) {
            image.status = 'failed';
            image.error = e.message;
        }
    },

    /** Inline (data:) and same-tab (blob:) images are read in the browser; web addresses go to the server. */
    async fileFor(image) {
        if (image.file) return image.file;
        if (!/^(data|blob):/i.test(image.src)) return null;
        try {
            const blob = await (await fetch(image.src)).blob();
            if (!blob.type.startsWith('image/')) return null;
            const name = `${image.alt.replace(/[^a-z0-9]+/gi, '-').replace(/^-|-$/g, '').toLowerCase().slice(0, 60) || 'pasted-image'}.${extensionFor(blob.type)}`;
            return new File([blob], name, { type: blob.type });
        } catch {
            return null;
        }
    },

    remove(index) {
        this.images.splice(index, 1);
    },

    guard(event) {
        if (this.uploading) {
            event.preventDefault();
            this.notice = 'Please wait until the photos have finished uploading.';
        }
    },
});
