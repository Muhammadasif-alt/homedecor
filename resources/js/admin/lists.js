let uid = 0;
const key = () => `k${Date.now()}${uid++}`;

/** Editable list of objects for JSON columns (<x-admin.repeater>). */
export const repeater = (initial = [], blank = {}) => ({
    items: initial.map((row) => ({ ...row, _key: key() })),
    add() {
        this.items.push({ ...blank, _key: key() });
    },
    remove(index) {
        this.items.splice(index, 1);
    },
    move(index, direction) {
        const target = index + direction;
        if (target < 0 || target >= this.items.length) return;
        [this.items[index], this.items[target]] = [this.items[target], this.items[index]];
    },
    pickMedia(row, field) {
        this.$store.media.choose((item) => {
            row[field] = item.id;
            row[`_${field}`] = item;
        });
    },
});

/**
 * Server-rendered rows cloned from a <template> (used where rows contain Trix editors,
 * which must exist in the DOM with their final attributes before they connect).
 * Row order in the DOM is the submitted order.
 */
export const rowList = (nextIndex = 0) => ({
    next: nextIndex,
    add() {
        // Unique key so new rows never collide with rows re-rendered from old input.
        const html = this.$refs.template.innerHTML.replaceAll('__INDEX__', `n${Date.now()}${this.next++}`);
        this.$refs.list.insertAdjacentHTML('beforeend', html);
    },
    remove(el) {
        const row = el.closest('[data-row]');
        if (row && confirm('Remove this block? It will be deleted when you save.')) row.remove();
    },
    move(el, direction) {
        const row = el.closest('[data-row]');
        const sibling = direction < 0 ? row.previousElementSibling : row.nextElementSibling;
        if (!sibling) return;
        direction < 0 ? sibling.before(row) : sibling.after(row);
    },
});
