/**
 * Media library: one global store powers the modal; small components (single picker,
 * ordered gallery, repeater media fields) ask it for images via `choose(callback)`.
 */
const meta = (name) => document.querySelector(`meta[name="${name}"]`)?.content ?? '';

export const mediaStore = () => ({
    open: false,
    multiple: false,
    items: [],
    selected: [],
    search: '',
    category: '',
    nextPage: 1,
    hasMore: false,
    loading: false,
    uploading: false,
    error: '',
    callback: null,

    choose(callback, { multiple = false } = {}) {
        this.callback = callback;
        this.multiple = multiple;
        this.selected = [];
        this.error = '';
        this.open = true;
        if (!this.items.length) this.fetch(true);
    },

    close() {
        this.open = false;
        this.callback = null;
    },

    async fetch(reset = false) {
        if (reset) {
            this.nextPage = 1;
            this.items = [];
        }
        this.loading = true;
        const params = new URLSearchParams({ page: this.nextPage, q: this.search, category: this.category });
        try {
            const response = await fetch(`${meta('media-library-url')}?${params}`, {
                headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            });
            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            const json = await response.json();
            const known = new Set(this.items.map((item) => item.id));
            this.items.push(...json.data.filter((item) => !known.has(item.id)));
            this.hasMore = Boolean(json.next_page_url);
            this.nextPage = json.current_page + 1;
        } catch (e) {
            this.error = 'Could not load the media library. Please try again.';
        } finally {
            this.loading = false;
        }
    },

    isSelected(item) {
        return this.selected.some((s) => s.id === item.id);
    },

    toggle(item) {
        if (this.isSelected(item)) {
            this.selected = this.selected.filter((s) => s.id !== item.id);
        } else {
            this.selected = this.multiple ? [...this.selected, item] : [item];
        }
    },

    confirm() {
        if (!this.selected.length) return;
        this.callback?.(this.multiple ? this.selected : this.selected[0]);
        this.close();
    },

    async upload(files) {
        if (!files?.length) return;
        this.uploading = true;
        this.error = '';
        const body = new FormData();
        [...files].forEach((file) => body.append('files[]', file));
        if (this.category) body.append('category', this.category);

        try {
            const response = await fetch(meta('media-upload-url'), {
                method: 'POST',
                body,
                headers: { Accept: 'application/json', 'X-CSRF-TOKEN': meta('csrf-token'), 'X-Requested-With': 'XMLHttpRequest' },
            });
            const json = await response.json().catch(() => ({}));
            if (!response.ok) {
                const errors = json.errors ? Object.values(json.errors).flat() : [json.message];
                throw new Error(errors.filter(Boolean).join(' ') || 'Upload failed.');
            }
            this.items.unshift(...json.data);
            json.data.forEach((item) => {
                if (this.multiple) this.selected.push(item);
                else this.selected = [item];
            });
        } catch (e) {
            this.error = e.message;
        } finally {
            this.uploading = false;
        }
    },
});

/** Single image field. */
export const mediaPicker = (initial = null) => ({
    media: initial,
    pick() {
        this.$store.media.choose((item) => (this.media = item));
    },
    clear() {
        this.media = null;
    },
});

/** Ordered list of images (optionally with captions). */
export const mediaGallery = (initial = []) => ({
    items: initial,
    add() {
        this.$store.media.choose((chosen) => {
            const existing = new Set(this.items.map((i) => i.id));
            chosen.filter((i) => !existing.has(i.id)).forEach((i) => this.items.push({ ...i, caption: '' }));
        }, { multiple: true });
    },
    remove(index) {
        this.items.splice(index, 1);
    },
    move(index, direction) {
        const target = index + direction;
        if (target < 0 || target >= this.items.length) return;
        [this.items[index], this.items[target]] = [this.items[target], this.items[index]];
    },
});
