import { describe, expect, it } from 'vitest'
import { t, type Locale } from './i18n'

const locales: Locale[] = ['vi', 'en', 'fr', 'ja', 'ko', 'zh', 'de', 'es']

describe('i18n', () => {
  it('translates admin and spinal keys in every locale', () => {
    for (const loc of locales) {
      expect(t(loc, 'nav_admin')).not.toBe('nav_admin')
      expect(t(loc, 'tbl_technique')).not.toBe('tbl_technique')
      expect(t(loc, 'page_spinal')).not.toBe('page_spinal')
    }
  })

  it('uses Vietnamese for nav_admin', () => {
    expect(t('vi', 'nav_admin')).toBe('Quản trị')
  })
})
