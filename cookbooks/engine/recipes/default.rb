
include_recipe 'memcached'

node.default['couchbase']['server']['username'] = "admin"
node.default['couchbase']['server']['password'] = "password"

node.default['couchbase']['client']['version'] = "2.6.2"

node.default['couchbase']['buckets']['mcache']['type'] = "memcached"
node.default['couchbase']['buckets']['mcache']['memory_quota_mb'] = 128

include_recipe 'couchbase::server'
include_recipe 'couchbase::client'
include_recipe 'couchbase::buckets'
