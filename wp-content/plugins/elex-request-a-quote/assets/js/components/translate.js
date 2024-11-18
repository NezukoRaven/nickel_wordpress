// eslint-disable-next-line no-underscore-dangle
export default function __(string, domain) {
    const { elex_raq_translations: elexRaqTranslations } = window;

    if (domain === 'elex-request-a-quote' && elexRaqTranslations && elexRaqTranslations?.[string]) {
        return elexRaqTranslations[string];
    }

    return string;
}