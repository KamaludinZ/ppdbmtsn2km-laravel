#!/bin/bash

###############################################################################
# Monitor Materialized Views Status
# Author: System Administrator
# Usage: ./scripts/monitor-views.sh
###############################################################################

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

# Database config
DB_USER="postgres"
DB_NAME="postgresppdb"

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}  Materialized Views Monitor${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""

# Function to execute SQL and format output
execute_sql() {
    psql -U $DB_USER -d $DB_NAME -t -A -c "$1"
}

# 1. Check if views exist
echo -e "${YELLOW}1. Materialized Views Status:${NC}"
echo ""
execute_sql "SELECT matviewname, pg_size_pretty(pg_total_relation_size(schemaname||'.'||matviewname)) as size FROM pg_matviews WHERE matviewname LIKE 'mv_%' ORDER BY matviewname;" | while IFS='|' read -r view size; do
    echo -e "  ${BLUE}$view${NC}: $size"
done
echo ""

# 2. Check last refresh time
echo -e "${YELLOW}2. Last Refresh Times:${NC}"
echo ""
echo -e "  ${BLUE}Dashboard Stats:${NC}"
execute_sql "SELECT last_refreshed FROM mv_dashboard_stats;" | xargs echo "    "

echo -e "  ${BLUE}Jalur Stats:${NC}"
execute_sql "SELECT last_refreshed FROM mv_jalur_stats LIMIT 1;" | xargs echo "    "

echo -e "  ${BLUE}Recent Pendaftar:${NC}"
execute_sql "SELECT last_refreshed FROM mv_recent_pendaftar LIMIT 1;" | xargs echo "    "

echo -e "  ${BLUE}Panitia Stats:${NC}"
execute_sql "SELECT last_refreshed FROM mv_panitia_stats;" | xargs echo "    "
echo ""

# 3. Check record counts
echo -e "${YELLOW}3. Record Counts:${NC}"
echo ""
echo -e "  ${BLUE}Dashboard Stats:${NC}"
execute_sql "SELECT total_pendaftar FROM mv_dashboard_stats;" | xargs echo "    Total Pendaftar: "

echo -e "  ${BLUE}Jalur Stats:${NC}"
execute_sql "SELECT COUNT(*) FROM mv_jalur_stats;" | xargs echo "    Total Jalur: "

echo -e "  ${BLUE}Recent Pendaftar:${NC}"
execute_sql "SELECT COUNT(*) FROM mv_recent_pendaftar;" | xargs echo "    Total Records: "

echo -e "  ${BLUE}Panitia Stats:${NC}"
execute_sql "SELECT panitia_aktif FROM mv_panitia_stats;" | xargs echo "    Panitia Aktif: "
echo ""

# 4. Check indexes
echo -e "${YELLOW}4. Indexes Status:${NC}"
echo ""
execute_sql "SELECT indexname, pg_size_pretty(pg_relation_size(indexname::regclass)) as size FROM pg_indexes WHERE tablename LIKE 'mv_%' ORDER BY indexname;" | while IFS='|' read -r index size; do
    echo -e "  ${BLUE}$index${NC}: $size"
done
echo ""

# 5. Performance comparison
echo -e "${YELLOW}5. Performance Test:${NC}"
echo ""
echo -e "  Testing view query speed..."
TIME1=$(execute_sql "EXPLAIN ANALYZE SELECT * FROM mv_dashboard_stats;" | grep "Execution Time" | awk '{print $3}')
echo -e "  ${BLUE}mv_dashboard_stats${NC} execution time: ${TIME1} ms"
echo ""

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}  Monitor Completed${NC}"
echo -e "${GREEN}========================================${NC}"
