import Themes from './components/Themes.vue';
import CardTheme from './components/CardTheme.vue';

vueApp.booting(vue => {
    vue.component('marketplace-themes', Themes);
    vue.component('marketplace-card-theme', CardTheme);
});

