<script>
    export let menuItems = [];
    let isActive = false;

    function handleClick()
    {
        isActive = !isActive;
        if (isActive) {
            document.body.classList.add('overflow-hidden', 'lg:overflow-visible');
        } else {
            document.body.classList.remove('overflow-hidden', 'lg:overflow-visible');
        }
    }
</script>

<button type="button" class="lg:hidden w-[40px] h-[71px]" on:click={handleClick}>
    {#if isActive}
        <div class="w-[40px] bg-white opacity-70 h-px rotate-45"></div>
        <div class="w-[40px] bg-white opacity-70 h-px -rotate-45"></div>
    {:else}
        <div class="flex flex-col gap-2.5">
            <div class="w-[40px] bg-white opacity-70 h-px"></div>
            <div class="w-[40px] bg-white opacity-70 h-px"></div>
            <div class="w-[40px] bg-white opacity-70 h-px"></div>
        </div>
    {/if}
</button>

{#if isActive}
    <div class="lg:hidden fixed left-0 right-0 bottom-0 top-[78px] bg-black overflow-y-scroll">
        <div class="gap-x-5 text-base mb-5">
            {#each menuItems as item}
                <a href="{ item.url }" class="block border-b border-gray-500 py-4 px-4">
                    <i class="las la-angle-right opacity-40 mr-2.5"></i>
                    <span class="text-lg">{ item.title }</span>
                </a>
            {/each}
        </div>

        <div class="text-center text-lg flex flex-col gap-2.5">
            <a href="mailto:info@veresk-landshaft.ru" class="underline">
                <i class="las la-envelope mr-1 la-lg opacity-40"></i> info@veresk-landshaft.ru
            </a>
            <a href="tel:+78129051863" class="underline">
                <i class="las la-phone mr-1 la-lg opacity-40"></i> +7 (812) 905-18-63
            </a>
            <a href="tel:+79219051863" class="underline">
                <i class="las la-phone mr-1 la-lg opacity-40"></i> +7 (921) 905-18-63
            </a>
            <a href="/" target="_blank">
                <i class="lab la-telegram mr-1 la-lg opacity-40"></i> Telegram
            </a>
            <a href="/" target="_blank">
                <i class="lab la-whatsapp mr-1 la-lg opacity-40"></i> Whatsapp
            </a>
        </div>
    </div>


{/if}