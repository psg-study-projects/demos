<template>
    <b-container fluid id="page-about" class="about">

        <b-row>
            <b-col>
                <Intro msg="foo"/>
            </b-col>
        </b-row>
        <b-row>
            <b-col>
                <Print msg="foo"/>
            </b-col>
        </b-row>
        <b-row>
            <b-col>
                <Social msg="foo"/>
            </b-col>
        </b-row>
        <b-row>
            <b-col>
                <Digital msg="foo"/>
            </b-col>
        </b-row>
        <b-row>
            <b-col>
                <Services msg="foo"/>
            </b-col>
        </b-row>

    </b-container>
</template>

<script>
// @ is an alias to /src
//import PageHeader from '@/components/PageHeader.vue'
import Intro from '@/components/about/Intro.vue'
import Print from '@/components/about/Print.vue'
import Social from '@/components/about/Social.vue'
import Digital from '@/components/about/Digital.vue'
import Services from '@/components/about/Services.vue'

export default {
  name: 'home',
  components: {
    //PageHeader,
    Intro,
    Print,
    Social,
    Digital,
    Services
  }
}
</script>

<style lang="scss">
@import '~bootstrap/scss/_functions';
@import '~bootstrap/scss/_variables';
@import '~bootstrap/scss/mixins/_breakpoints';

#page-about .tag-background_image {
    background-size: cover;
    background-position: center center;
    background-repeat: no-repeat;
}

#wrap-about_print, 
#wrap-about_digital, 
#wrap-about_social {
    h2 {
        justify-content: center;
        display: flex;
        align-items: center;
    }
    .component-footer p {
        display: inline-block;
    }
}

// Responsive breakpoints
@include media-breakpoint-up(xs) {
    #page-about .template-wrap {
        height: 100vh;
    }
    h2 > img {
        width: 3.2em;
        margin-right: 0.3em;
        font-size: 0.8em;
    }
    h2 > span {
        font-size: 0.8em;
    }
    .component-footer p {
        margin-top: 1em;
        font-size: 0.9em;
        width: 80%;
    }
    h6 {
        font-size: 0.5em;
        margin-bottom: 1em;
    }
}

@include media-breakpoint-up(md) {
    #page-about .template-wrap {
        height: 100vh;
    }
    h2 > img {
        width: 3.2em;
        margin-right: 0.3em;
    }
    .component-footer p {
        width: 80%;
        font-size: 2.5em;
        line-height: 1.1em;
        margin-top: 0.5em;
    }
    h6 {
        font-size: 1em;
    }
}

@include media-breakpoint-up(lg) {
    .component-footer p {
        width: 50%;
    }
}

// =========================
// Transitions
// =========================

#wrap-about_print, 
#wrap-about_digital, 
#wrap-about_services, 
#wrap-about_social {

    // --- h2 Header Logo ---

    h2 span {
        transform: translateX(1.5em);
        opacity: 0;
        transition: all 2.2s;
    }
    h2 span.in-viewport {
        opacity: 1;
        transform: translateX(0);
    }
    h2 img {
        transform: translateX(-1.5em);
        opacity: 0;
        transition: all 0.9s;
        transition-delay: 1.0s;
    }
    h2 img.in-viewport {
        opacity: 1;
        transform: translateX(0);
    }
}

// --- Component Footer ---
.component-footer p {
    transform: translateY(1.8em);
    opacity: 0;
    transition: all 2.2s;
}
.component-footer p.in-viewport {
    opacity: 1;
    transform: translateY(0);
}
.component-footer h6 {
    transform: scale(0.8);
    opacity: 0;
    transition: all 1.5s;
    transition-delay: 0.5s;
}
.component-footer h6.in-viewport {
    opacity: 1;
    transform: scale(1);
}
</style>
