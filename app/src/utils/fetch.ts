const baseUrl = import.meta.env.VITE_API_BASE_URL

interface Options {
  body: BodyInit
  headers: HeadersInit
  method: 'POST' | 'GET' | 'PUT' | 'PATCH' | 'DELETE'
  signal: AbortSignal
}

export type Fetch = (path: string, options?: Partial<Options>) => Promise<Response>

export function createFetch(url: string, options?: Partial<Options>): Fetch {
  return async function (path: string, userOptions?: Partial<Options>) {
    const fetchUrl = [baseUrl, url, path].join('')

    const response = await fetch(fetchUrl, {
      body: userOptions?.body ?? options?.body,
      headers: {
        ...options?.headers,
        ...userOptions?.headers,
      },
      method: options?.method ?? userOptions?.method,
      signal: options?.signal ?? userOptions?.signal,
    })

    if (!response.ok)
      throw new Error(`Fetch failed with status: ${response.status}`)

    return response
  }
}
