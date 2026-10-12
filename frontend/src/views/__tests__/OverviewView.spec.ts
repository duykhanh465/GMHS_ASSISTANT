import { afterEach, beforeEach, describe, expect, it, vi } from 'vitest'
import { flushPromises, mount } from '@vue/test-utils'
import { createPinia } from 'pinia'
import OverviewView from '../OverviewView.vue'
import api from '@/api/client'

vi.mock('@/api/client', () => ({ default: { get: vi.fn(), post: vi.fn() } }))

const patients = [
  { id: 1, code: 'DEMO-001', full_name: 'Demo A', age: 45, gender: 'male', height_cm: '170', weight_kg: '70', asa_class: 'II', respiratory_condition: 'routine', surgery_note: 'Demo surgery A' },
  { id: 2, code: 'DEMO-002', full_name: 'Demo B', age: 60, gender: 'female', height_cm: '150', weight_kg: '65', asa_class: 'III', respiratory_condition: 'routine', surgery_note: 'Demo surgery B' },
]

function render() {
  return mount(OverviewView, {
    global: { plugins: [createPinia()], stubs: { RouterLink: true } },
  })
}

beforeEach(() => {
  localStorage.clear()
  vi.resetAllMocks()
})
afterEach(() => vi.restoreAllMocks())

describe('Overview patient selection', () => {
  it('loads the list and the first patient on mount, then switches patients', async () => {
    vi.mocked(api.get).mockImplementation(async (url) => {
      if (url === '/patients') return { data: { data: patients, total: 2 } }
      return { data: patients.find(p => url === `/patients/${p.id}`) }
    })
    const wrapper = render()
    await flushPromises()

    expect(api.get).toHaveBeenCalledWith('/patients')
    expect(api.get).toHaveBeenCalledWith('/patients/1')
    expect(wrapper.findAll('select option')).toHaveLength(2)
    expect(wrapper.text()).toContain('Demo surgery A')

    await wrapper.get('select').setValue('2')
    await flushPromises()
    expect(api.get).toHaveBeenCalledWith('/patients/2')
    expect(wrapper.text()).toContain('Demo surgery B')
    expect(wrapper.text()).not.toContain('Demo surgery A')
    wrapper.unmount()
  })

  it('keeps patient cards hidden when the list is empty', async () => {
    vi.mocked(api.get).mockResolvedValue({ data: { data: [], total: 0 } })
    const wrapper = render()
    await flushPromises()
    expect(api.get).toHaveBeenCalledWith('/patients')
    expect(wrapper.text()).toContain('Chưa có bệnh nhân')
    expect(wrapper.find('h2').exists()).toBe(false)
    wrapper.unmount()
  })

  it('shows an API error without rendering a null patient', async () => {
    vi.spyOn(console, 'error').mockImplementation(() => {})
    vi.mocked(api.get).mockRejectedValue({ response: { status: 403 } })
    const wrapper = render()
    await flushPromises()
    expect(wrapper.text()).toContain('Không có quyền xem (403).')
    expect(wrapper.find('h2').exists()).toBe(false)
    wrapper.unmount()
  })
})
