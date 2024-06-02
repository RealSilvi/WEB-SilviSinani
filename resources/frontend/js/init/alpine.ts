import { Alpine, Livewire } from '../livewire';
import '../components';
import collapse from '@alpinejs/collapse';

Alpine.plugin(collapse);

Livewire.start();
