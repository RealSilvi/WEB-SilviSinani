import { Alpine, Livewire } from '../livewire';
import '../components';
import collapse from '@alpinejs/collapse';
import intersect from '@alpinejs/intersect';

Alpine.plugin(intersect);
Alpine.plugin(collapse);

Livewire.start();
