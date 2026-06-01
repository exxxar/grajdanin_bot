<template>

    <div class="card rounded-4">
        <div class="card-body">



            <button
                v-if="canNativeShare"
                class="btn btn-primary mb-3 w-100 p-3 rounded-4"
                @click="nativeShare"
            >
                <i class="fa-solid fa-share-nodes me-2"></i>
                Поделиться
            </button>

            <div class="d-flex flex-wrap gap-2 justify-content-center">

                <button
                    class="btn btn-primary rounded-circle share-btn"
                    @click="shareTelegram"
                    title="Telegram"
                >
                    <i class="fa-brands fa-telegram"></i>
                </button>

                <button
                    class="btn btn-primary rounded-circle share-btn"
                    @click="shareVk"
                    title="VK"
                >
                    <i class="fa-brands fa-vk"></i>
                </button>

                <button
                    class="btn btn-primary rounded-circle share-btn"
                    @click="shareWhatsapp"
                    title="WhatsApp"
                >
                    <i class="fa-brands fa-whatsapp"></i>
                </button>

                <button
                    class="btn btn-primary rounded-circle share-btn"
                    @click="shareEmail"
                    title="Почта"
                >
                    <i class="fa-solid fa-envelope"></i>
                </button>

            </div>
            <hr>

            <div class="input-group">

                <input
                    type="text"
                    class="form-control"
                    :value="url"
                    readonly
                >

                <button
                    class="btn border-light rounded-end-4 text-primary"
                    @click="copyLink"
                >
                    <i class="fa-solid fa-copy"></i>
                </button>

            </div>



        </div>
    </div>

</template>

<script>
export default {
    name: "ShareLinks",

    props: {

        title: {
            type: String,
            default: document.title
        },

        text: {
            type: String,
            default: ""
        },

        url: {
            type: String,
            default: () => window.location.href
        }

    },

    computed: {

        encodedUrl() {
            return encodeURIComponent(this.url);
        },

        encodedTitle() {
            return encodeURIComponent(this.title);
        },

        encodedText() {
            return encodeURIComponent(this.text);
        },

        canNativeShare() {
            return typeof navigator.share === "function";
        }

    },

    methods: {

        open(url) {
            window.open(
                url,
                "_blank",
                "width=700,height=600"
            );
        },

        shareTelegram() {
            this.open(
                `https://t.me/share/url?url=${this.encodedUrl}&text=${this.encodedTitle}`
            );
        },

        shareVk() {
            this.open(
                `https://vk.com/share.php?url=${this.encodedUrl}`
            );
        },

        shareWhatsapp() {
            this.open(
                `https://api.whatsapp.com/send?text=${this.encodedTitle}%20${this.encodedUrl}`
            );
        },

        shareFacebook() {
            this.open(
                `https://www.facebook.com/sharer/sharer.php?u=${this.encodedUrl}`
            );
        },

        shareTwitter() {
            this.open(
                `https://twitter.com/intent/tweet?url=${this.encodedUrl}&text=${this.encodedTitle}`
            );
        },

        shareEmail() {
            window.location.href =
                `mailto:?subject=${this.encodedTitle}&body=${this.encodedUrl}`;
        },

        async copyLink() {

            try {

                await navigator.clipboard.writeText(
                    this.url
                );

                alert("Ссылка скопирована");

            } catch (e) {

                console.error(e);

            }

        },

        async nativeShare() {

            try {

                await navigator.share({
                    title: this.title,
                    text: this.text,
                    url: this.url
                });

            } catch (e) {

                console.error(e);

            }

        }

    }

}
</script>
<style>
.share-btn {
    width: 48px;
    height: 48px;

    display: flex;
    align-items: center;
    justify-content: center;

    padding: 0;

    font-size: 1.25rem;
}
</style>
