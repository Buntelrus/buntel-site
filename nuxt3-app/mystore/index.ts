import {reactive, computed} from "vue";
import fakeApi, {IGlobal, IPage} from "~/utils/api";
import page from "../../framework/packages/nuxt3/src/pages/runtime/page.vue";

// makes all non array|bool|string types nullable
type NullableState<T> = {
  [K in keyof T]:
    T[K] extends Array<any> ? T[K] :
    T[K] extends boolean ? T[K] :
    T[K] extends string ? T[K] : T[K] | null
}

interface State {
  global: IGlobal,
  pages: IPage[]
}

export const state = reactive<NullableState<State>>({
  global: null,
  pages: [],
})

type CapitalizedSetter<T extends string> = `set${Capitalize<T>}`

type MutationType = {
  [K in keyof State as CapitalizedSetter<K>]: (payload: State[K]) => void
}

const mutations: MutationType = {
  setGlobal(global) {
    state.global = global
  },
  setPages(pages) {
    state.pages = pages
  }
}

export function commit<K extends keyof State>(type: CapitalizedSetter<K>, payload: State[K]) {
  (<any>mutations[type])(payload)
}

const actions = {
  fetchData() {
    const asyncActions: Promise<void>[] = []
    asyncActions.push(
      fakeApi('global').then(global => {
        commit('setGlobal', global)
      })
    )
    asyncActions.push(
      fakeApi('pages').then(pages => {
        commit('setPages', pages)
      })
    )
    return Promise.all(asyncActions)
  }
}

export function dispatch<K extends keyof typeof actions>(type: K, ...args: Parameters<typeof actions[K]>) {
  (<any>actions[type])(...args)
}
