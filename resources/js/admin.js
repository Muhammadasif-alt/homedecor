import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import 'trix';
import 'trix/dist/trix.css';
import { mediaStore, mediaPicker, mediaGallery } from './admin/media';
import { repeater, rowList } from './admin/lists';
import { articlePaste } from './admin/paste';

// Images belong in the media library, not inline in rich text.
document.addEventListener('trix-file-accept', (event) => event.preventDefault());

Alpine.plugin(collapse);
Alpine.store('media', mediaStore());
Alpine.data('mediaPicker', mediaPicker);
Alpine.data('mediaGallery', mediaGallery);
Alpine.data('repeater', repeater);
Alpine.data('rowList', rowList);
Alpine.data('articlePaste', articlePaste);

window.Alpine = Alpine;
Alpine.start();
