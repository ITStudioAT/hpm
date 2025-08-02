export function deepMergeDefaults(target, defaults) {
  const result = Array.isArray(defaults) ? [] : {};

  const keys = new Set([
    ...Object.keys(defaults || {}),
    ...Object.keys(target || {}),
  ]);

  for (const key of keys) {
    const defaultVal = defaults?.[key];
    const targetVal = target?.[key];

    if (
      defaultVal &&
      typeof defaultVal === "object" &&
      !Array.isArray(defaultVal)
    ) {
      result[key] = deepMergeDefaults(targetVal || {}, defaultVal);
    } else {
      result[key] = targetVal !== undefined ? targetVal : defaultVal;
    }
  }

  return result;
}
