#!/bin/sh

set -eu

RUNTIME=${RUNTIME:-/runtime}
LOG_DIR="${RUNTIME}/log"
LOG_FILE="${LOG_DIR}/entrypoint.log"
APP_USER=${APP_USER:-developer}
APP_SUDO="sudo -E -u ${APP_USER}"

if [ -z "${USER_ID:-}" ]; then
    echo "Set USER_ID environment as current user id"
    exit 1
fi
if [ -z "${GROUP_ID:-}" ]; then
    echo "Set GROUP_ID environment as current user group id"
    exit 1
fi

# Creating same user, as system inside container using USER_ID and GROUP_ID
if ! id "${APP_USER}" > /dev/null 2>&1; then
    getent group "${GROUP_ID}" | cut -d: -f1 | xargs -r delgroup 2>/dev/null
    addgroup -g "${GROUP_ID}" "${APP_USER}"
    getent passwd "${USER_ID}" | cut -d: -f1 | xargs -r deluser 2>/dev/null
    adduser -S -u "${USER_ID}" -G "${APP_USER}" "${APP_USER}"
    getent group "${GROUP_ID}"
    getent passwd "${USER_ID}"
fi

chown -R "${USER_ID}":"${GROUP_ID}" "${RUNTIME}"
${APP_SUDO} mkdir -p "${LOG_DIR}"
${APP_SUDO} touch "${LOG_FILE}"

log_info() {
    echo -e "\n### [$(date '+%F %T %Z')] ${1}" >> ${LOG_FILE}
}

log_info "Exec with sudo ${*}"
${APP_SUDO} "${@}"
log_info "exit ${?}: '${*}'"
