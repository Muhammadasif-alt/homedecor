/**
 * Small Alpine components shared across pages.
 */

/** Accessible image lightbox. Expects `items` = [{src, srcset, alt, caption}]. */
export function lightbox(items = []) {
    return {
        items,
        index: null,
        get current() { return this.index === null ? null : this.items[this.index]; },
        open(i) { this.index = i; document.body.style.overflow = 'hidden'; },
        close() { this.index = null; document.body.style.overflow = ''; },
        next() { this.index = (this.index + 1) % this.items.length; },
        prev() { this.index = (this.index - 1 + this.items.length) % this.items.length; },
        onKey(e) {
            if (this.index === null) return;
            if (e.key === 'Escape') this.close();
            if (e.key === 'ArrowRight') this.next();
            if (e.key === 'ArrowLeft') this.prev();
        },
    };
}

/** Before/after comparison slider driven by a range input (keyboard accessible). */
export function beforeAfter() {
    return { position: 50 };
}

/** Client-side category filter + "load more" for the portfolio grid. */
export function galleryFilter(pageSize = 9) {
    return {
        active: 'all',
        visible: pageSize,
        pageSize,
        matches(category) { return this.active === 'all' || this.active === category; },
        shown(category, position) { return this.matches(category) && position < this.visible; },
        select(category) { this.active = category; this.visible = this.pageSize; },
        more() { this.visible += this.pageSize; },
    };
}
